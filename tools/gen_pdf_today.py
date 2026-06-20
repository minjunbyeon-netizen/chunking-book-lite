"""Regenerate only today's corrected Day PDFs and zip them to the Desktop.

Today (2026-06-02) we fixed typos in book-lite.html for these Days:
  12, 23, 39, 40, 77, 228
Slugs (image-path verbs) are unchanged by the text fixes, so filenames stay
the same; only the rendered text inside the PDF changes.
"""

import sys
import zipfile
from pathlib import Path

sys.stdout.reconfigure(encoding="utf-8")
sys.stderr.reconfigure(encoding="utf-8")

sys.path.insert(0, str(Path(__file__).parent))
from gen_pdf_by_day import (  # noqa: E402
    build_day_chunks,
    slug_for_day,
    folder_for_day,
    gen_one,
    OUT_BASE,
    HTML,
    TMP_BASE,
)

DAYS = [179, 204, 224]
DESKTOP = Path(r"C:\Users\USER\Desktop")
ZIP_PATH = DESKTOP / "청킹_수정_Day179-204-224_20260621.zip"


def main():
    TMP_BASE.mkdir(parents=True, exist_ok=True)
    html_text = HTML.read_text(encoding="utf-8")
    day_chunks = build_day_chunks(html_text)

    targets = []
    for d in DAYS:
        slug = slug_for_day(day_chunks[d])
        folder = folder_for_day(d)
        out_path = OUT_BASE / folder / f"day{d}_{slug}.pdf"
        targets.append((d, slug, out_path))
        # delete old so gen_one re-renders (it has skip-if-exists)
        if out_path.exists():
            out_path.unlink()
            print(f"deleted old day{d} -> {out_path.name}")

    made = []
    for d, slug, out_path in targets:
        day, ok, msg = gen_one(d, slug)
        status = "OK" if ok else "FAIL"
        print(f"day{d} {status} ({msg}) -> {out_path.name}")
        if ok:
            made.append(out_path)

    if len(made) != len(DAYS):
        print(f"\nABORT zip: only {len(made)}/{len(DAYS)} regenerated")
        sys.exit(1)

    with zipfile.ZipFile(ZIP_PATH, "w", zipfile.ZIP_DEFLATED) as zf:
        for p in made:
            zf.write(p, arcname=p.name)
    size_kb = ZIP_PATH.stat().st_size // 1024
    print(f"\nZIP OK: {ZIP_PATH} ({size_kb}KB, {len(made)} files)")


if __name__ == "__main__":
    main()
