"""Dictionary spell check for book-lite.html visible English text.

Uses pyspellchecker (bundled English frequency dictionary). Flags any word
in <h3> chunk titles or <div class="eng-sentence"> that the dictionary does
not know, with the Day it appears in and a suggested correction.
"""

import re
import sys
from collections import defaultdict
from spellchecker import SpellChecker

sys.stdout.reconfigure(encoding="utf-8")

HTML = r"C:\dev\web\chunking\book-lite.html"

TAG = re.compile(r"<[^>]+>")
DAY = re.compile(r"day(\d+)")
H3 = re.compile(r"<h3>(.*?)</h3>", re.S)
SENT = re.compile(r'<div class="eng-sentence">(.*?)</div>', re.S)
WORD = re.compile(r"[A-Za-z]+")


def main():
    text = open(HTML, encoding="utf-8").read()
    spell = SpellChecker()

    ctx = defaultdict(set)   # word -> {(day, snippet)}
    all_words = set()

    cur_day = 0
    for line in text.splitlines():
        m = DAY.search(line)
        if m:
            cur_day = int(m.group(1))
        for block in H3.findall(line) + SENT.findall(line):
            clean = TAG.sub("", block).strip()
            for w in WORD.findall(clean):
                lw = w.lower()
                if len(lw) < 3:
                    continue
                all_words.add(lw)
                ctx[lw].add((cur_day, clean))

    unknown = sorted(spell.unknown(all_words))
    if not unknown:
        print("의심 단어 없음 — 보이는 영어 텍스트가 모두 사전에 있음")
        return

    print(f"사전에 없는 단어 {len(unknown)}건:\n")
    rows = []
    for w in unknown:
        days = sorted({d for d, _ in ctx[w]})
        sample = sorted({s for _, s in ctx[w]})[:3]
        sugg = spell.correction(w)
        rows.append((w, sugg, days, sample))
    # sort: fewest days first (isolated = more likely typo)
    rows.sort(key=lambda r: (len(r[2]), r[0]))
    for w, sugg, days, sample in rows:
        print(f"  '{w}'  ->  추천: '{sugg}'   Day {days}")
        for s in sample:
            print(f"      | {s}")
        print()


if __name__ == "__main__":
    main()
