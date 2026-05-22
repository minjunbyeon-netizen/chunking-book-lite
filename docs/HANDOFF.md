# HANDOFF - 2026-05-23 02:00

> 직전 세션: `book-lite.html` (총 1004 시트, Day 1~250 + 표지/공동저자/책소개/마무리) 디자인 다듬기 + 출판사 납품용 PDF 5분할 생성

## 완료
- [x] 책소개 3단계(제1·2·3단계) 사이 간격 2배 (margin-top 14→28px)
- [x] 책소개 z-content `height: auto` + bg-deco 제거 (하단 빈 영역 축소)
- [x] `.cover-sheet .z-content` 상하 padding 20 → 70px
- [x] `.note-area` 박스(배경·테두리·radius) 완전 제거 → light/dark 카드 모두 텍스트만 노출 (`.note-area.dark` 룰도 redundant 라 같이 삭제)
- [x] `.img-container` 비율 1/1 → **16/15** 조정, `max-height: calc(100% - 46px)` (이미지 짤림 + 텍스트 자리 부족 해결)
- [x] PDF 분할용 query param 핸들러 JS 삽입 (`book-lite.html` 라인 324 근처) — `?range=1-50` 등으로 시트 필터, `startDay > 1` 이면 표지 자동 숨김
- [x] Chrome headless 로 5개 PDF 생성:
  - `pdf-output/chunking-day-1-50.pdf` (166 MB, 표지·공동저자·책소개 포함)
  - `pdf-output/chunking-day-51-100.pdf` (164 MB)
  - `pdf-output/chunking-day-101-150.pdf` (171 MB)
  - `pdf-output/chunking-day-151-200.pdf` (171 MB)
  - `pdf-output/chunking-day-201-250.pdf` (165 MB)
- [x] `pdf-output/chunking-book-lite-PDFs.zip` (688 MB) — 5개 zip 묶음
- [x] GitHub Pages 라이브: https://minjunbyeon-netizen.github.io/chunking-book-lite/book-lite.html

## 진행중
- [ ] **PDF 사이즈 검토** — 광고주 → 출판사 납품용. 사용자는 "원본 용량 할 필요 없음 (URL 수준)" 명시했는데 결과물이 PDF 개당 165~170MB, zip 688MB. 사용자 확인 대기 중.
  - 중단 지점: 사용자에게 "원본 그대로 / 압축 / 클라우드 링크 그대로" 3지선다 옵션 제시한 상태
  - 다음 스텝 (사용자 선택에 따라):
    - 옵션 1 → PDF 하나 열어보고 OK 면 그대로 전달
    - 옵션 2 → ghostscript 설치 후 `gswin64c -sDEVICE=pdfwrite -dPDFSETTINGS=/ebook` 으로 50~80MB 압축
    - 옵션 3 → Drive/Notion 링크 전달 (그대로)

## 대기
- [ ] 광고주에게 받아둘 출판사 스펙 5가지 (판형·PDF/X 표준·색공간·bleed·DPI) — 진짜 인쇄 납품이면 현재 RGB 96DPI PDF 는 부적합 가능. 사용자가 광고주에게 문의 필요.
- [ ] Day 4~250 페이지에서 이미지·텍스트 짤림 추가 확인 (현재는 Day 1·2·3 까지만 시각 검증함)

## 결정사항 / 주의
- **이미지 비율**: 16/15 (거의 정사각형, 살짝만 가로형). 시도 이력: 1/1(원본 짤림) → 4/3(과함, 텍스트 98px) → 7/6(반, 텍스트 76px) → **16/15(텍스트 60px, 최종)**
- **note-area 박스 제거**는 light·dark 모두 적용됨. 의도는 "main-point 카드 텍스트만 노출" 패턴을 light 카드에도 확장.
- **PDF 분할 정책**: 옵션 A 채택 — 표지/공동저자/책소개는 첫 PDF (1-50) 에만, 나머지 4개는 Day 부터 바로 시작. JS 핸들러가 `startDay > 1` 일 때 day-badge 없는 시트 (=cover-sheet) 자동 숨김으로 처리.
- **PDF 생성 명령** (재현용):
  ```powershell
  & 'C:\Program Files\Google\Chrome\Application\chrome.exe' --headless=new --disable-gpu --no-sandbox `
    --user-data-dir='C:\dev\chrome-pdf-tmp' --virtual-time-budget=60000 `
    --run-all-compositor-stages-before-draw --no-pdf-header-footer `
    "--print-to-pdf=<OUT>.pdf" "http://localhost:8765/book-lite.html?range=<START>-<END>"
  ```
- **로컬 서버**: python `-m http.server 8765` (백그라운드 ID `bquan6lam` — 이전 세션. 새 세션에서는 다시 띄워야 함).
- **메모리 룰**: "PDF Release 는 사용자 OK URL 의 HTML 라이브와 항상 같은 내용. 변경 시 즉시 재빌드". HTML 추가 변경하면 PDF 도 재생성 필요.

## 다음 세션 권장 첫 프롬프트
`/resume`
