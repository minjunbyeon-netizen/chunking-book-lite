import re, io, sys
sys.stdout.reconfigure(encoding="utf-8")
html = io.open(r"C:\dev\web\chunking\book-lite.html", "r", encoding="utf-8").read()
glosses = re.findall(r"</h3><span>([^<]+)</span>", html)

def hasjong(ch):
    o = ord(ch)
    if 0xAC00 <= o <= 0xD7A3:
        return (o - 0xAC00) % 28 != 0
    return None

# (받침必요particle, 받침無particle)
pairs = [("을", "를"), ("이", "가"), ("은", "는"), ("과", "와")]
need_jong = {a for a, b in pairs}
no_jong = {b for a, b in pairs}
bad = {}
for g in glosses:
    for tok in g.split():
        if len(tok) >= 2 and tok[-1] in (need_jong | no_jong):
            jong = hasjong(tok[-2])
            if jong is None:
                continue
            if jong and tok[-1] in no_jong:
                bad[g] = tok[-1] + "→받침형"
            if (not jong) and tok[-1] in need_jong:
                bad[g] = tok[-1] + "→무받침형"

for g in sorted(bad):
    print(bad[g], "|", g)
print("COUNT", len(bad))
