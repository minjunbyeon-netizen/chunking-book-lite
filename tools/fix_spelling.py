"""Apply the clear English typo fixes found by spellcheck_book.py.

Only unambiguous misspellings. Real words flagged by the dictionary but
intentionally kept: app, cafe, KakaoTalk, chunking, kickboard, and the
HTML entity artifact 'quot' (&quot;).
"""

import re
import sys

sys.stdout.reconfigure(encoding="utf-8")
HTML = r"C:\dev\web\chunking\book-lite.html"

# base lowercase typo -> correction. Both lowercase and Capitalized forms fixed.
PAIRS = [
    ("achieveing", "achieving"),
    ("afffection", "affection"),
    ("believ", "believe"),
    ("cenvention", "convention"),
    ("christams", "christmas"),
    ("cinamon", "cinnamon"),
    ("ckicken", "chicken"),
    ("cofffee", "coffee"),
    ("feelingss", "feelings"),
    ("grabing", "grabbing"),
    ("hepls", "helps"),
    ("llullaby", "lullaby"),
    ("meetting", "meeting"),
    ("memu", "menu"),
    ("opportuity", "opportunity"),
    ("pouringg", "pouring"),
    ("preapring", "preparing"),
    ("schdeule", "schedule"),
    ("telent", "talent"),
    ("vacatioon", "vacation"),
]
# literal odd-case fixes (capital I instead of l)
LITERAL = [
    ("heIps", "helps"),
]


def main():
    text = open(HTML, encoding="utf-8").read()
    total = 0
    for typo, correct in PAIRS:
        for t, c in ((typo, correct), (typo.capitalize(), correct.capitalize())):
            n = len(re.findall(r"\b" + re.escape(t) + r"\b", text))
            if n:
                text = re.sub(r"\b" + re.escape(t) + r"\b", c, text)
                total += n
                print(f"  {t} -> {c}  ({n})")
    for t, c in LITERAL:
        n = text.count(t)
        if n:
            text = text.replace(t, c)
            total += n
            print(f"  {t} -> {c}  ({n})")
    open(HTML, "w", encoding="utf-8").write(text)
    print(f"\nTotal replacements: {total}")


if __name__ == "__main__":
    main()
