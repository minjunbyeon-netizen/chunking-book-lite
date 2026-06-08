"""Force-regenerate ALL 250 per-Day PDFs from the current book-lite.html.

Unlike gen_pdf_by_day.main() (which skips existing files), this deletes each
target first so every Day re-renders against the latest corrected HTML.
PDFs are gitignored, so there is no repo churn.
"""

import sys
import time
import threading
from pathlib import Path
from concurrent.futures import ThreadPoolExecutor, as_completed

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

WORKERS = 6


def force_one(day: int, slug: str):
    out_path = OUT_BASE / folder_for_day(day) / f"day{day}_{slug}.pdf"
    if out_path.exists():
        out_path.unlink()
    return gen_one(day, slug)


def main():
    TMP_BASE.mkdir(parents=True, exist_ok=True)
    day_chunks = build_day_chunks(HTML.read_text(encoding="utf-8"))

    tasks = []
    for day in range(1, 251):
        chunks = day_chunks.get(day, [])
        if not chunks:
            print(f"SKIP day{day} (no chunks)")
            continue
        tasks.append((day, slug_for_day(chunks)))

    print(f"Force-regenerating {len(tasks)} Days, workers={WORKERS}", flush=True)
    start = time.time()
    done = 0
    failed = []
    with ThreadPoolExecutor(max_workers=WORKERS) as pool:
        futures = {pool.submit(force_one, d, s): d for d, s in tasks}
        for fut in as_completed(futures):
            day, ok, msg = fut.result()
            done += 1
            if not ok:
                failed.append((day, msg))
                print(f"[{done}/{len(tasks)}] day{day} FAIL - {msg}", flush=True)
            elif done % 10 == 0 or done == len(tasks):
                el = time.time() - start
                rate = done / el if el else 0
                eta = (len(tasks) - done) / rate if rate else 0
                print(f"[{done}/{len(tasks)}] {el:.0f}s elapsed, ETA {eta:.0f}s", flush=True)

    el = time.time() - start
    print(f"\nDONE in {el:.0f}s. Success {len(tasks)-len(failed)}, Failed {len(failed)}", flush=True)
    if failed:
        for d, m in failed[:30]:
            print(f"  day{d}: {m}", flush=True)
        sys.exit(1)


if __name__ == "__main__":
    main()
