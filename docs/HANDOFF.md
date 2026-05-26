# HANDOFF - 2026-05-26 12:17

> 직전 세션: `book-lite.html` day-badge 3자리 Day 줄바꿈 fix → 라이브 배포 → 매니저 전달용 URL 정리

## 완료
- [x] `.header-left { width: 100px → 110px }` (book-lite.html:132) — 컨테이너 +10%
- [x] `.day-badge { font-size: 1.15rem → 1.035rem }` + `white-space: nowrap` (book-lite.html:134~) — 폰트 -10% + 줄바꿈 차단
- [x] CSS 룰 한 곳 수정 = 1004 시트 전체 동시 적용 확인 (Day 100·150·200·250 동일하게 fix 됨)
- [x] commit `aa54bd1` + push → GitHub Pages 자동 배포 → 라이브 HTTP 200 + `width:110px` grep 확인
- [x] 실수로 떨어진 잡파일 `UsersUSERAppDataLocalTemplive-book-lite.html` (Git Bash 가 Windows 경로 잘못 해석해 생긴 7MB) 제거 — commit `9c12552`
- [x] `.gitignore` 에 재발 방지 패턴 3종 추가 (`UsersUSER*`, `Users*AppData*`, `*AppDataLocalTemp*`)
- [x] **톤 격식체 override 메모리 저장** — `memory/tone-formal.md` 신설 + MEMORY.md 인덱스 갱신. 이 프로젝트 한정 "~입니다/~해주세요" 사용
- [x] 매니저 전달용 최종 URL 안내 완료:
  - `https://minjunbyeon-netizen.github.io/chunking-book-lite/book-lite.html`
  - 구간별: `?range=1-50` / `?range=51-100` / ... `?range=201-250` (PDF 와 같은 50 단위)

## 진행중
- (없음 — 매니저 회신 대기)

## 대기 (이전 인계장에서 그대로 이월)
- [ ] **PDF 5분할 재빌드 결정 대기** — 오늘 HTML 만 fix, `pdf-output/chunking-day-*.pdf` 5개는 옛 HTML(Day 102 줄바꿈 잔존) 기준. 사용자 "PDF 는 내가 말하기 전까지 하지마" 명시 → Claude 자가 발의 X
- [ ] **PDF 사이즈 검토** — 광고주 → 출판사 납품용. 개당 165~170MB, zip 688MB. 사용자 옵션 선택 대기:
  - 옵션 1 → 원본 그대로 전달
  - 옵션 2 → ghostscript 압축 (`gswin64c -sDEVICE=pdfwrite -dPDFSETTINGS=/ebook`, 50~80MB 목표)
  - 옵션 3 → Drive/Notion 링크 그대로
- [ ] 광고주에게 받아둘 출판사 스펙 5가지 (판형·PDF/X 표준·색공간·bleed·DPI)
- [ ] Day 4~250 페이지 시각 검수 (이미지·텍스트 짤림 추가 확인) — 오늘은 Day 100~110 day-badge 만 검증

## 결정사항 / 주의
- **톤 (강제, 이 프로젝트 한정)**: 응답 본문 모두 격식체. 글로벌 친근체 기본값 override. SSOT = `memory/tone-formal.md`
- **PDF 정책**: 사용자 명시 — Claude 가 먼저 재빌드 발의 X. 사용자 명령 때만 진입
- **메모리 룰 `pdf-html-sync.md`**: PDF Release = OK URL HTML 라이브와 항상 동일 내용. 현재 HTML/PDF 비동기 상태 → 사용자 명령 떨어지면 즉시 재빌드
- **PDF 생성 명령** (재현용):
  ```powershell
  & 'C:\Program Files\Google\Chrome\Application\chrome.exe' --headless=new --disable-gpu --no-sandbox `
    --user-data-dir='C:\dev\chrome-pdf-tmp' --virtual-time-budget=60000 `
    --run-all-compositor-stages-before-draw --no-pdf-header-footer `
    "--print-to-pdf=<OUT>.pdf" "http://localhost:8765/book-lite.html?range=<START>-<END>"
  ```
- **로컬 서버**: python `-m http.server 8765` (백그라운드 ID `bqb487ut4` — 이번 세션). 새 세션 진입 시 다시 띄워야 함
- **GitHub Pages**: master 브랜치 자동 배포. push 후 1~2분이면 라이브 반영
- **Git Bash 함정**: `curl -o C:\Users\...` 처럼 Windows 경로 그대로 넘기면 백슬래시 사라져 cwd 에 `CUsersUser...` 잡파일 생성. 임시파일은 `/tmp/...` 또는 `$TEMP` 사용

## 다음 세션 권장 첫 프롬프트
`/resume`
