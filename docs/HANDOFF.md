# HANDOFF - 2026-06-03 19:48

> 직전 세션: 한글 글로스 전수 검수(5,996쌍) → 오타·오역 12건 도출 + 매니저 보고용 PDF/메일 작성.
> **다음 세션 트리거: 매니저 답신 오면 A·B 9건(즉시) + C 3건(매니저 결정 따라) 정정.**

## 오늘 완료
- [x] 한글 조사 오타 2건 정정 (이미 라이브 반영·푸시 완료)
  - Day 218 `찾다 에스컬레이털를` → `에스컬레이터를`
  - Day 168 `영양분를 주다 식물에게` → `영양분을`
- [x] 글로스 5,996쌍 전수 검수 (10구간 병렬 에이전트) → 12건 도출
- [x] 검수 보고 HTML/PDF 생성 + 바탕화면 저장
  - `오타검수_보고.html`, `오타검수_보고.pdf` (프로젝트 루트)
  - `C:\Users\USER\Desktop\청킹_오타검수_보고.pdf`
- [x] 매니저 보고용 메일 초안 작성 (아래 "메일 초안" 참고 — 아직 발송 안 함)
- [x] 신설 스크립트: `tools/check_korean_particle.py`(조사 점검), `tools/audit_gloss_dump.py`(검수용 덤프 생성)

## 대기 (매니저 답신 후 실행) — 정정 목록 SSOT

> 전부 `book-lite.html` 의 `<h3>…</h3><span>한글</span>` gloss span 수정. 이미지 src·영어 h3 은 안 건드림.
> **수정은 매니저 확인 전까지 금지 (사용자 명시 "절대 미리 바꾸지 말 것").**

### A. 철자/조사 오타 6건 (확정 — 답신 오면 바로)
- [ ] Day 85  take a bribe       `받다 뇌믈을` → `받다 뇌물을`
- [ ] Day 98  lose the key       `잃다 열쇄를` → `잃다 열쇠를`
- [ ] Day 148 receive the deposit `받다 보증금` → `받다 보증금을` (조사 누락)
- [ ] Day 191 face the difficulty `직면하다 어려음을` → `직면하다 어려움을`
- [ ] Day 197 prepare thoroughly  `준비하다 철저학게` → `준비하다 철저하게`
- [ ] Day 205 wait for the subway `기다리다 자하철을` → `기다리다 지하철을`

### B. 오역 3건 (확정 — 답신 오면 바로)
- [ ] Day 45  take a chance      `받아들이다 위험을` → `받아들이다 기회를`
- [ ] Day 73  deserve the credit `받을 만하다 신뢰를` → `받을 만하다 공로를`
      ⚠ `받을 만하다 신뢰를` 은 HTML에 2곳 — Edit 시 반드시 `<h3>deserve the credit</h3>` 줄만 대상.
      나머지 `<h3>deserve the trust</h3>` 의 신뢰를 는 정상이라 손대면 안 됨.
- [ ] Day 109 have a sweet voice `가지고 있다 좋은 목소리를` → `가지고 있다 달콤한 목소리를`

### C. 경계 사례 3건 (매니저 결정 따라 — 고칠지 말지 답 받고)
- [ ] Day 45  take criticism        `받다 비난을` → `받다 비판을`  (h3 take criticism 줄만)
- [ ] Day 73  deserve the criticism `받을 만하다 비난을` → `받을 만하다 비판을`
      ⚠ 같은 Day73 `deserve the blame` 의 비난을은 정상 — 손대지 말 것.
- [ ] Day 124 drink the cider       `마시다 사과주스를` → `마시다 사이다를`  (cider 미국식=사과주스라 경계)

### D. 영어 원문 측 오타 3건 (참고 — 별도 결정)
- [ ] Day 179 `buy a ballon` → `balloon` (영어 철자)
- [ ] `stand in a  line`, `check the  zip code` — 영어에 공백 2칸
- [ ] Day 195 `escape from the crowd` 군중에게서 → 군중에서 (의미는 통함)

### 정정 후 필수 후속
- [ ] [[pdf-html-sync]] — 바뀐 Day들 by-day PDF 재빌드 (`tools/gen_pdf_today.py` DAYS 리스트 교체)
      대상 Day: 218·168 + (확정 시) 85·98·148·191·197·205·45·73·109 + (경계 채택분)
- [ ] commit + push (라이브 URL 자동 반영)

## 결정사항 / 주의
- **톤 격식체 유지** ([[tone-formal]]) — "~입니다/~해주세요"
- **수정 보류 룰** — 사용자가 "절대 미리 바꾸지 말고 보고부터" 지시. 매니저 답신 전 gloss 수정 금지
- **검수 방식** — pyspellchecker(영어) + 조사 규칙 코드(`check_korean_particle.py`) + LLM 통독(10구간). 청킹 어순·직역체는 오류 아님(플래그 X)
- **검수 덤프 재생성** — `python tools\audit_gloss_dump.py` → `tools/_gloss_audit.txt` (5,996행, 임시 산출물)
- 라이브 URL: minjunbyeon-netizen.github.io/chunking-book-lite/book-lite.html

## 메일 초안 (매니저 발송용 — 미발송)
제목: [청킹 교재] 글로스 오타·오역 검수 결과 보고 (정정 전 확인 요청)
- 본문 요약: A 철자/조사 6 · B 오역 3 · C 경계 3 · D 영어측 3. A·B 9건 즉시 가능, C 3건 결정 요청.
- 첨부: 청킹_오타검수_보고.pdf

## 다음 세션 권장 첫 프롬프트
`/resume` → 매니저 답신 내용 알려주시면 A·B 정정 + C 결정분 반영
