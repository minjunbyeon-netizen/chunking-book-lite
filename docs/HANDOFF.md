# HANDOFF - 2026-05-26 15:39

> 직전 세션: book-lite.html → Day 1~250 개별 PDF 9폴더 분할 생성 + 검증 + zip 묶음 완성

## 완료
- [x] `book-lite.html` 에 `?nocover=1` 파라미터 신설 — 표지·책소개 3장 제외 단일 Day 렌더 지원 (line 326~349)
- [x] `tools/gen_pdf_by_day.py` 신설 — Day 1~250 슬러그 자동 추출(이미지 경로 grep) + Chrome headless 병렬(4 worker) PDF 생성기
- [x] `tools/validate_pdfs.py` 신설 — pypdf 로 페이지 수·Day 텍스트 매칭·청크 단어 매칭 4중 검증
- [x] `pdf-output/by-day/` 9폴더 생성 + 250개 개별 Day PDF 채움 (총 1011.5MB)
  - 01_Hope-and-Practice (10) / 02_Morning-Routine (19) / 03_School-Life (58) / 04_Exercise-and-Sports (12)
  - 05_Food-and-Cooking (39) / 06_Daily-Life (56) / 07_Transportation-and-Travel (30) / 08_Health-and-Medicine (10) / 09_Evening-Routine (16)
- [x] 파일명 규칙 `day{N}_{chunk1-chunk2-chunk3}.pdf` 적용 (사용자 지정 형식)
- [x] 250개 전수 pypdf 검증 통과 (Passed 250 / Failed 0)
- [x] Day 14·134·250 playwright 시각 캡처 검증 — 시트 4장 정상 렌더
- [x] `pdf-output/zip/` 9개 폴더별 zip 압축 (Optimal, 합 834.4MB)
- [x] `pdf-output/chunking-all-9folders.zip` 마스터 zip (NoCompression, 834.5MB) — 광고주 1회 전송용
- [x] 이전 HANDOFF.md → `docs/archive/HANDOFF-2026-05-26.md` 이동

## 진행중
- (없음 — 9폴더 PDF/zip 작업 모두 닫힘)

## 대기 (사용자 선택지)
- [ ] **ghostscript PDF 압축** — 현재 개별 4MB / 9폴더 합 834MB. `gswin64c -dPDFSETTINGS=/ebook` 로 50~70% 추가 축소 가능 (총 ~300MB 예상). 사용자 명령 시 진입
- [ ] **광고주/매니저 전달** — Google Drive·WeTransfer·카카오톡 PC 중 선택 (이메일 첨부 불가, 25MB 초과)
- [ ] **기존 5분할 PDF (`pdf-output/chunking-day-*.pdf`) 재빌드 여부** — HTML 에 `?nocover=1` 추가됐지만 옛 5분할은 그대로. 사용자 명시 명령 시만 진입 (PDF-HTML 동기 룰 적용)
- [ ] **이전 세션 이월**: 광고주에게 받아둘 출판사 스펙 5가지 (판형·PDF/X 표준·색공간·bleed·DPI)

## 결정사항 / 주의
- **톤 격식체 유지** — `memory/tone-formal.md` 의 "~입니다/~해주세요" 룰 적용 중
- **PDF·HTML 동기 룰** — book-lite.html 변경(nocover=1 추가)으로 라이브 URL 와 250개 by-day PDF 가 동기 상태. 이후 HTML 변경 시 by-day PDF 도 재빌드 필요
- **표지 3장 제외 정책** — `?nocover=1` 로 모든 by-day PDF 가 시트 4장(=청크 페이지 4)만 포함. 표지/책소개 빠짐
- **슬러그 출처** — `img-lite/final/day{N}/{NN}_{chunk}` 경로 grep 자동 추출, 같은 청크 중복 제거하지 않고 등장 순 그대로 (Day 1: have-change-start)
- **Chrome headless 함정 2건**
  - (1) worker_id 를 `idx % 8` 로 할당하면 같은 user-data-dir 두 Chrome 충돌 → `threading.get_ident()` 로 thread-local dir 필수
  - (2) 8 worker 동시 + 120s timeout 은 부하 과다로 50KB 깨진 PDF 양산. **4 worker + 240s timeout + 200KB 임계 검증** 조합이 안정
- **검증 4중 체크** — `tools/validate_pdfs.py --full` 로 전수 검증 가능 (페이지 수·Day 번호 일치·청크 단어 일치·파일 크기)
- **PDF 정책 불변** — 사용자 명시 명령 시만 PDF 작업 진입. Claude 자가 발의 X (이전 HANDOFF 명시 정책 유지)
- **py 출력 인코딩 함정** — Windows cp949 환경에서 em dash(`—`) 등 비-cp949 글자 print 시 UnicodeEncodeError. `sys.stdout.reconfigure(encoding="utf-8")` + `$env:PYTHONIOENCODING='utf-8'` 필수

## 주요 산출물 경로
- 개별 PDF: `pdf-output/by-day/{01_Hope-and-Practice ... 09_Evening-Routine}/day{N}_{slug}.pdf` (250개)
- 폴더별 zip: `pdf-output/zip/{01_Hope-and-Practice ... 09_Evening-Routine}.zip` (9개, 합 834.4MB)
- 마스터 zip: `pdf-output/chunking-all-9folders.zip` (834.5MB, 9 zip 내장)
- 생성 스크립트: `tools/gen_pdf_by_day.py`, `tools/validate_pdfs.py`
- HTML 변경: `book-lite.html` line 326~349 (`?nocover=1` 파라미터 처리)

## 재생성 명령 (필요 시)
```powershell
# 로컬 서버 (포트 8765)
python -m http.server 8765

# 250개 by-day PDF 재생성 (skip-if-exists 내장)
$env:PYTHONIOENCODING='utf-8'
python tools\gen_pdf_by_day.py

# 전수 검증
python tools\validate_pdfs.py --full
```

## 다음 세션 권장 첫 프롬프트
`/resume`
