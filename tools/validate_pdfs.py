"""Validate random sample of generated PDFs.

Checks per PDF:
1. Opens without error
2. Page count >= 1
3. Day text in PDF matches Day number in filename
4. Contains at least one English chunk word from filename slug
"""

import sys
import re
import random
from pathlib import Path
from pypdf import PdfReader

sys.stdout.reconfigure(encoding="utf-8")

ROOT = Path(r"C:\dev\web\chunking\pdf-output\by-day")
SAMPLE_SIZE = 30
SEED = 20260526
FULL = "--full" in sys.argv


def parse_filename(name: str) -> tuple[int, list[str]]:
    """day123_chunk1-chunk2-chunk3.pdf -> (123, ['chunk1', 'chunk2', 'chunk3'])"""
    m = re.match(r"day(\d+)_(.+)\.pdf", name)
    if not m:
        raise ValueError(f"bad filename: {name}")
    return int(m.group(1)), m.group(2).split("-")


def validate(pdf_path: Path) -> tuple[bool, list[str]]:
    issues = []
    name = pdf_path.name
    try:
        day_num, chunks = parse_filename(name)
    except Exception as e:
        return False, [f"filename parse: {e}"]

    try:
        reader = PdfReader(str(pdf_path))
        n_pages = len(reader.pages)
    except Exception as e:
        return False, [f"pypdf open: {e}"]

    if n_pages == 0:
        issues.append(f"zero pages")
        return False, issues

    # Extract text from first page
    try:
        text = reader.pages[0].extract_text() or ""
    except Exception as e:
        text = ""
        issues.append(f"text extract fail: {e}")

    # Check Day N text
    day_pattern = re.compile(rf"Day\s*{day_num}\b")
    if not day_pattern.search(text):
        # Try all pages
        all_text = ""
        for p in reader.pages:
            try:
                all_text += p.extract_text() or ""
            except Exception:
                pass
        if not day_pattern.search(all_text):
            issues.append(f"no 'Day {day_num}' text found")

    # Check at least one chunk word appears
    all_text = ""
    for p in reader.pages:
        try:
            all_text += (p.extract_text() or "").lower()
        except Exception:
            pass
    matched = [c for c in chunks if c.lower() in all_text]
    if not matched:
        issues.append(f"no chunk words {chunks} found in text")

    # File size
    size_kb = pdf_path.stat().st_size // 1024
    if size_kb < 200:
        issues.append(f"size too small: {size_kb}KB")

    ok = len(issues) == 0
    return ok, issues + [f"pages={n_pages}", f"size={size_kb}KB", f"chunks_found={matched}"]


def main():
    all_pdfs = sorted(ROOT.rglob("*.pdf"))
    print(f"Total PDFs on disk: {len(all_pdfs)}")

    if FULL:
        sample = sorted(all_pdfs, key=lambda p: int(re.match(r"day(\d+)_", p.name).group(1)))
        mode = "FULL"
    else:
        random.seed(SEED)
        sample = random.sample(all_pdfs, min(SAMPLE_SIZE, len(all_pdfs)))
        sample.sort(key=lambda p: int(re.match(r"day(\d+)_", p.name).group(1)))
        mode = f"random sample seed={SEED}"

    print(f"\nValidating {len(sample)} PDFs ({mode})...\n")
    failures = []
    for pdf in sample:
        ok, info = validate(pdf)
        status = "OK  " if ok else "FAIL"
        folder = pdf.parent.name
        if not ok or not FULL:
            print(f"[{status}] {folder}/{pdf.name}")
            for line in info:
                marker = "       " if "=" in line and "found:" not in line else "    -> "
                print(f"{marker}{line}")
        if not ok:
            failures.append((pdf.name, info))

    print(f"\n=== Summary ===")
    print(f"Sample: {len(sample)}, Passed: {len(sample) - len(failures)}, Failed: {len(failures)}")
    if failures:
        print("Failed files:")
        for name, info in failures:
            print(f"  - {name}: {info[:3]}")
        sys.exit(1)
    else:
        print("All validated PDFs OK.")


if __name__ == "__main__":
    main()
