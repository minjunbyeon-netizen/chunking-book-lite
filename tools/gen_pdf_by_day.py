"""Generate 250 per-Day PDFs in parallel using Chrome headless.

Uses ThreadPoolExecutor with N workers, each spawning a Chrome instance
with a unique user-data-dir to avoid lock conflicts.
"""

import re
import sys
import subprocess
import shutil
from pathlib import Path
from concurrent.futures import ThreadPoolExecutor, as_completed
from collections import defaultdict
import threading
import time

sys.stdout.reconfigure(encoding="utf-8")
sys.stderr.reconfigure(encoding="utf-8")

ROOT = Path(r"C:\dev\web\chunking")
HTML = ROOT / "book-lite.html"
OUT_BASE = ROOT / "pdf-output" / "by-day"
CHROME = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
SERVER = "http://localhost:8765/book-lite.html"
TMP_BASE = Path(r"C:\dev\chrome-pdf-tmp-pool")
WORKERS = 4
MIN_PDF_SIZE = 200_000  # bytes; < 200KB = corrupt
GEN_TIMEOUT = 240  # seconds per Chrome invocation

DAY_TO_FOLDER = [
    (1, 10, "01_Hope-and-Practice"),
    (11, 29, "02_Morning-Routine"),
    (30, 87, "03_School-Life"),
    (88, 99, "04_Exercise-and-Sports"),
    (100, 138, "05_Food-and-Cooking"),
    (139, 194, "06_Daily-Life"),
    (195, 224, "07_Transportation-and-Travel"),
    (225, 234, "08_Health-and-Medicine"),
    (235, 250, "09_Evening-Routine"),
]


def folder_for_day(day: int) -> str:
    for lo, hi, name in DAY_TO_FOLDER:
        if lo <= day <= hi:
            return name
    raise ValueError(f"Day {day} out of range")


def build_day_chunks(html_text: str) -> dict[int, list[str]]:
    """day_num -> [chunk_name, ...] in order of first appearance."""
    pattern = re.compile(r"img-lite/final/day(\d+)/(\d+)_([a-zA-Z]+)")
    seen = defaultdict(list)
    for m in pattern.finditer(html_text):
        day = int(m.group(1))
        order = int(m.group(2))
        chunk = m.group(3).lower()
        # dedupe within day, keep first-seen order
        if not any(c[0] == order for c in seen[day]):
            seen[day].append((order, chunk))
    result = {}
    for day, items in seen.items():
        items.sort(key=lambda x: x[0])
        result[day] = [c for _, c in items]
    return result


def slug_for_day(chunks: list[str]) -> str:
    # dedupe consecutive duplicates while preserving order
    out = []
    for c in chunks:
        if not out or out[-1] != c:
            out.append(c)
    return "-".join(out)


def gen_one(day: int, slug: str) -> tuple[int, bool, str]:
    folder = folder_for_day(day)
    out_path = OUT_BASE / folder / f"day{day}_{slug}.pdf"
    out_path.parent.mkdir(parents=True, exist_ok=True)
    if out_path.exists() and out_path.stat().st_size > MIN_PDF_SIZE:
        return (day, True, f"skip (exists {out_path.stat().st_size // 1024}KB)")
    # Thread-local user-data-dir: stable per worker thread, unique across concurrent threads
    user_data = TMP_BASE / f"t{threading.get_ident()}"
    user_data.mkdir(parents=True, exist_ok=True)
    url = f"{SERVER}?range={day}-{day}&nocover=1"
    cmd = [
        CHROME,
        "--headless=new",
        "--disable-gpu",
        "--no-sandbox",
        f"--user-data-dir={user_data}",
        "--virtual-time-budget=30000",
        "--no-pdf-header-footer",
        f"--print-to-pdf={out_path}",
        url,
    ]
    try:
        result = subprocess.run(
            cmd, capture_output=True, text=True, timeout=GEN_TIMEOUT
        )
        if out_path.exists() and out_path.stat().st_size > MIN_PDF_SIZE:
            return (day, True, f"{out_path.stat().st_size // 1024}KB")
        return (day, False, f"file missing or too small. stderr: {result.stderr[-200:]}")
    except subprocess.TimeoutExpired:
        return (day, False, f"timeout {GEN_TIMEOUT}s")
    except Exception as e:
        return (day, False, f"exception: {e}")


def main():
    TMP_BASE.mkdir(parents=True, exist_ok=True)

    html_text = HTML.read_text(encoding="utf-8")
    day_chunks = build_day_chunks(html_text)
    missing = [d for d in range(1, 251) if d not in day_chunks]
    if missing:
        print(f"WARN: {len(missing)} days missing chunks: {missing[:10]}...")

    tasks = []
    for day in range(1, 251):
        chunks = day_chunks.get(day, [])
        if not chunks:
            print(f"SKIP day{day} (no chunks)")
            continue
        slug = slug_for_day(chunks)
        tasks.append((day, slug))

    print(f"Total tasks: {len(tasks)}, workers: {WORKERS}")
    start = time.time()
    done = 0
    failed = []

    with ThreadPoolExecutor(max_workers=WORKERS) as pool:
        futures = {}
        for day, slug in tasks:
            futures[pool.submit(gen_one, day, slug)] = day
        for fut in as_completed(futures):
            day, ok, msg = fut.result()
            done += 1
            if ok:
                if done % 10 == 0 or done == len(tasks):
                    elapsed = time.time() - start
                    rate = done / elapsed if elapsed > 0 else 0
                    eta = (len(tasks) - done) / rate if rate > 0 else 0
                    print(
                        f"[{done}/{len(tasks)}] day{day} OK ({msg}) - "
                        f"{elapsed:.0f}s elapsed, ETA {eta:.0f}s"
                    )
            else:
                failed.append((day, msg))
                print(f"[{done}/{len(tasks)}] day{day} FAIL - {msg}")

    elapsed = time.time() - start
    print(f"\nDone in {elapsed:.0f}s. Success: {len(tasks) - len(failed)}, Failed: {len(failed)}")
    if failed:
        print("Failed days:")
        for d, m in failed[:20]:
            print(f"  day{d}: {m}")
        sys.exit(1)


if __name__ == "__main__":
    main()
