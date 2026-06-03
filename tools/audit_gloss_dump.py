import re, io, sys
sys.stdout.reconfigure(encoding="utf-8")
html = io.open(r"C:\dev\web\chunking\book-lite.html", "r", encoding="utf-8").read()

# 각 위치의 직전 Day 번호를 추적하기 위해 토큰 단위로 스캔
day_marks = [(m.start(), int(m.group(1))) for m in re.finditer(r">Day (\d+)", html)]

def day_at(pos):
    d = None
    for start, n in day_marks:
        if start <= pos:
            d = n
        else:
            break
    return d

def clean(s):
    return re.sub(r"<[^>]+>", "", s).strip()

seen = set()
out = []
for m in re.finditer(r"<h3>(.*?)</h3><span>([^<]+)</span>", html):
    en = clean(m.group(1))
    ko = m.group(2).strip()
    key = (en, ko)
    if key in seen:
        continue
    seen.add(key)
    out.append((day_at(m.start()), en, ko))

with io.open(r"C:\dev\web\chunking\tools\_gloss_audit.txt", "w", encoding="utf-8") as f:
    for i, (d, en, ko) in enumerate(out, 1):
        f.write(f"{i}\tDay{d}\t{en}\t{ko}\n")
print("wrote", len(out), "rows")
