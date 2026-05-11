<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Chunking English E-Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Jua&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ---------------------------------
           [CSS 변수 정의]
        --------------------------------- */
        :root {
            --brand-bg: #FFF8FA;
            --brand-white: #FFFFFF;
            --primary: #FF7E96;
            --primary-light: #FFEFF2;
            --secondary: #7CC29D;
            --accent: #FFCE54;
            --text-main: #2A2F32;
            --text-sub: #6E767B;
            --text-gray: #9A9EA3;
            --line-gray: #F0E4E7;
            --red-point: #FA4252;
            --dark-box: #2A2F32;
            --font-kid: 'Jua', sans-serif;
            --highlight-blue: #2563EB; /* 파란색 강조 컬러 추가 */
        }

        /* ---------------------------------
           [기본 레이아웃 및 초기화]
        --------------------------------- */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @media print {
            @page { size: 210mm 297mm; margin: 0; }
            html, body { margin: 0 !important; padding: 0 !important; background-color: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .no-print-temp { display: none !important; }
            .sheet {
                width: 210mm;
                height: auto;
                min-height: 297mm;
                max-height: 297mm;
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                page-break-after: always;
                break-after: page;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .sheet:last-child { page-break-after: auto; break-after: auto; }
            .page-break { page-break-before: always; break-before: page; }
        }

        body {
            background-color: #4A4E53;
            font-family: 'Poppins', 'Pretendard', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 0;
            color: var(--text-main);
        }

        .sheet {
            width: 210mm;
            height: 297mm;
            background-color: var(--brand-bg);
            position: relative;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-bottom: 40px;
            padding: 12mm;
            border-radius: 8px;
        }

        .bg-deco {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(var(--line-gray) 1.5px, transparent 1.5px);
            background-size: 28px 28px; z-index: 0; opacity: 0.7; pointer-events: none;
        }

        .z-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* ---------------------------------
           [상단 기능 (PDF 다운로드)]
        --------------------------------- */
        .top-controls { width: 210mm; display: flex; justify-content: flex-end; margin-bottom: 12px; }
        .btn-pdf-download {
            background: #1A1A1A; color: white; border: none; padding: 10px 20px; border-radius: 12px;
            font-family: 'Pretendard', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); transition: all 0.2s; display: flex; align-items: center; gap: 8px;
        }
        .btn-pdf-download:hover { transform: translateY(-2px); background: #000000; box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4); }

        /* PDF 모달창 */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .modal-overlay.active { display: flex; }
        .modal-content { background: white; padding: 28px; border-radius: 16px; width: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); font-family: 'Pretendard', sans-serif; }
        .modal-content h2 { margin-bottom: 16px; font-size: 1.3rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px; }
        .day-select-wrapper { margin-bottom: 16px; }
        .day-select-wrapper label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; color: var(--text-sub); }
        .day-select { width: 100%; padding: 10px; border-radius: 8px; border: 2px solid #F0E4E7; font-family: 'Poppins', 'Pretendard', sans-serif; font-weight: 600; font-size: 1rem; color: var(--text-main); outline: none; cursor: pointer; }
        .print-option-label { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 2px solid #F0E4E7; border-radius: 12px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; font-weight: 600; font-size: 0.95rem; }
        .print-option-label:hover { border-color: #3B82F6; background: #EFF6FF; }
        .print-option-label input[type="radio"] { accent-color: #3B82F6; transform: scale(1.2); }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
        .btn-modal { padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 1rem; transition: all 0.2s; }
        .btn-cancel { background: #F0E4E7; color: #6E767B; }
        .btn-cancel:hover { background: #E2D5D8; }
        .btn-confirm { background: #3B82F6; color: white; }

        /* ---------------------------------
           [상단 헤더 스타일]
        --------------------------------- */
        .main-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 0.8rem; padding-bottom: 0.6rem;
            border-bottom: 2px solid rgba(240, 228, 231, 0.6); min-height: 60px; gap: 10px; flex-shrink: 0;
        }
        .header-left { display: flex; align-items: center; width: 100px; }

        .day-badge {
            background: var(--primary);
            color: #FFFFFF; padding: 6px 16px; border-radius: 8px;
            font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(255, 126, 150, 0.25); letter-spacing: 0.5px;
        }

        .main-header .header-center { flex: 1; text-align: right; white-space: nowrap; overflow: visible; }
        .header-center h1 { font-family: var(--font-kid); font-weight: 700; font-size: 1.85rem; margin-bottom: 0px; }
        .sub-header-text { font-family: var(--font-kid); font-size: 0.95rem; font-weight: 400; color: var(--text-sub); opacity: 0.8; letter-spacing: 0.3px; }

        .header-right { display: flex; justify-content: flex-end; gap: 8px; align-items: center; }
        .header-right .mode-wrapper { display: flex; align-items: center; gap: 8px; }

        .app-mode-btn {
            background: transparent !important; border: none; padding: 6px 10px; border-radius: 8px;
            display: flex; align-items: center; gap: 6px; font-family: 'Pretendard', sans-serif; font-weight: 600; font-size: 1rem;
            color: var(--text-gray) !important; cursor: pointer;
        }
        .app-mode-btn img { width: 28px; height: 28px; object-fit: contain; }
        .app-mode-btn.active { font-weight: 700; color: var(--text-main) !important; }

        .font-red { color: var(--red-point); }
        .drop-shadow { filter: drop-shadow(0 2px 4px rgba(250, 66, 82, 0.2)); }

        /* ---------------------------------
           [표지(Cover) 전용 스타일 추가]
        --------------------------------- */
        .cover-sheet .z-content { padding: 20px 30px; }
        .author-section { background: #FFFFFF; padding: 18px; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid #FDF4F6; display: flex; gap: 20px; align-items: flex-start; margin-bottom: 16px; }
        .author-section img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
        .author-section .img-box { width: 100px; height: 100px; flex-shrink: 0; border-radius: 12px; overflow: hidden; background: #F8FAFC; border: 1px solid #F1F5F9; }
        .author-section h3 { font-size: 1.25rem; margin-bottom: 8px; color: var(--text-main); font-family: 'Pretendard', sans-serif; font-weight: 800; display: flex; align-items: center; gap: 8px;}
        .author-section ul { list-style: none; padding: 0; line-height: 1.5; font-size: 0.9rem; color: var(--text-main); }
        .author-section li { position: relative; padding-left: 14px; margin-bottom: 4px; }
        .author-section li::before { content: '·'; position: absolute; left: 0; top: -1px; color: var(--primary); font-weight: 800; font-size: 1.2rem; }

        /* ---------------------------------
           [청킹 그리드 (Page 1~3)]
        --------------------------------- */
        .chunk-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(3, 1fr); gap: 12px; flex-grow: 1; margin-bottom: 5px; }
        .chunk-card { background: #FFFFFF; padding: 8px; border-radius: 14px; border: 1px solid #FDF4F6; display: flex; flex-direction: column; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .chunk-card.main-point { border: 2px solid rgba(255, 126, 150, 0.4); box-shadow: 0 8px 20px rgba(255,126,150,0.15); }

        /* 이미지 컨테이너 정방형 비율 적용 */
        .img-container {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #FAFAFA;
            border-radius: 10px;
            margin-bottom: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }
        .img-container img { width: 100%; height: 100%; object-fit: cover; }

        .note-area { position: relative; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; border-radius: 10px; background: #FFFAFB; border: 1px solid #FFEFF2; overflow: hidden; }
        .note-line { position: absolute; inset: 0; background-image: repeating-linear-gradient(transparent, transparent 18px, rgba(255, 126, 150, 0.12) 19px); background-position: top; }
        .note-margin { position: absolute; left: 14px; top: 0; bottom: 0; width: 1.5px; background: rgba(250, 66, 82, 0.25); }
        .note-text-wrap { position: relative; z-index: 10; text-align: center; background: transparent; padding: 4px 10px; border: none; box-shadow: none; }
        .note-text-wrap h3 { font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--text-main); margin-bottom: 0px; letter-spacing: 0.3px; line-height: 1.2; }
        .note-text-wrap span { font-family: 'Pretendard', sans-serif; font-size: 0.8rem; font-weight: 500; color: var(--text-sub); }

        .mode-switch-card { display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 12px; background: transparent; border: 2px dashed #E2E8F0; padding: 10px; }
        .mode-switch-card .app-mode-btn { width: 100%; background: transparent !important; border: none !important; box-shadow: none !important; padding: 8px; justify-content: center; transition: none !important; color: #64748B !important; font-size: 1.15rem; gap: 10px; }
        .mode-switch-card .app-mode-btn img { width: 68px; height: 68px; object-fit: contain; } /* 청킹기본, 변화 아이콘 대폭 확대 */
        .mode-switch-card .app-mode-btn:hover { transform: none !important; background: transparent !important; }
        .mode-switch-card .app-mode-btn.active { background: transparent !important; color: var(--primary) !important; font-weight: 700; }

        /* ---------------------------------
           [10문장 매직 카드 리스트 (Page 4)]
        --------------------------------- */
        .magic-card-list { display: flex; flex-direction: column; gap: 8px; flex-grow: 1; margin-bottom: 5px; }
        .magic-card { flex: 1; background: #FFFFFF; border: 1px solid rgba(255, 126, 150, 0.15); border-radius: 14px; padding: 6px 14px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); position: relative; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }

        .magic-number-tag {
            background: var(--primary); color: #FFFFFF; border-radius: 8px; width: 32px; height: 32px;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.05rem;
            display: flex; align-items: center; justify-content: center; border: none; box-shadow: 0 2px 6px rgba(255, 126, 150, 0.25); flex-shrink: 0;
        }

        .magic-content { flex: 1; min-width: 0; display: flex; flex-direction: row; align-items: center; gap: 12px; padding: 2px 0; }

        /* 아이콘 수직/수평 고정 레이아웃 (Grid 적용으로 너비 최적화) */
        .grammar-visual-box {
            display: grid;
            grid-template-columns: 36px 12px minmax(60px, auto) 12px 36px; /* 5개 열로 분할하여 각각의 위치를 강제 고정 및 좁게 설정 */
            align-items: center;
            justify-items: center;
            width: max-content;
            min-width: 190px;
            box-sizing: border-box;
            gap: 4px; padding: 4px 8px; background: #FFF5F7; border: none; border-radius: 10px; flex-shrink: 0; box-shadow: inset 0 1px 3px rgba(255,126,150,0.06);
        }

        .wizard-icon {
            width: 36px;
            height: 36px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(255,126,150,0.2));
        }

        .magic-connector-tag { white-space: nowrap; background: #FFFFFF; border: none; color: #FF5A82; font-family: 'Pretendard', sans-serif; font-weight: 600; font-size: 0.85rem; padding: 2px 6px; border-radius: 6px; box-shadow: 0 2px 4px rgba(255,126,150,0.1); letter-spacing: 0.5px; }
        .text-plus { color: var(--primary); font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 700; opacity: 0.8; }

        .magic-text-box { flex: 1; background: transparent; border: none; box-shadow: none; padding: 2px 12px; border-left: 2px solid #FFEFF2; border-radius: 0; display: flex; flex-direction: column; justify-content: center; }
        /* 긴 문장 자동 줄바꿈 처리 */
        .eng-sentence { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.1rem; color: var(--text-main); line-height: 1.3; margin-bottom: 4px; word-break: keep-all; white-space: normal; }
        .kor-sentence { font-family: 'Pretendard', sans-serif; font-weight: 400; font-size: 0.9rem; color: var(--text-sub); line-height: 1.3; word-break: keep-all; white-space: normal; }

        /* 문장 내 파란색 강조 텍스트 클래스 */
        .text-blue {
            color: var(--highlight-blue);
            font-weight: 700;
        }

        /* ---------------------------------
           [푸터 및 저작권 문구]
        --------------------------------- */
        .footer-wrapper { margin-top: auto; display: flex; flex-direction: column; gap: 10px; }
        .copyright-box { background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 8px 14px; text-align: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
        .copyright-text { font-family: 'Pretendard', sans-serif; font-size: 8.5px; color: #64748B; line-height: 1.5; letter-spacing: -0.2px; margin: 0; }
        .copyright-text strong { color: #475569; font-weight: 600; }
        .page-footer { padding-top: 8px; border-top: 1px solid var(--line-gray); display: flex; justify-content: space-between; font-size: 11px; color: #A0AAB2; font-family: 'Poppins', sans-serif; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
    </style>

    <script>
        /** [PDF 다운로드 (출력) 로직] */
        function openPdfModal() {
            const daySelect = document.getElementById('daySelect');
            if (daySelect.options.length === 0) {
                for (let i = 1; i <= 250; i++) {
                    let opt = document.createElement('option');
                    opt.value = i;
                    opt.innerHTML = 'Day ' + i;
                    daySelect.appendChild(opt);
                }
            }
            document.getElementById('pdfModal').classList.add('active');
        }

        function closePdfModal() {
            document.getElementById('pdfModal').classList.remove('active');
        }

        function printSelectedPages() {
            const selectedDay = parseInt(document.getElementById('daySelect').value);
            const choice = document.querySelector('input[name="printOption"]:checked').value;
            const allSheets = document.querySelectorAll('.sheet');
            const dataSheets = document.querySelectorAll('.sheet:not(.cover-sheet)');

            allSheets.forEach(sheet => sheet.classList.remove('no-print-temp'));

            if (choice !== 'all') {
                allSheets.forEach(sheet => {
                    if(sheet.classList.contains('cover-sheet')) {
                        sheet.classList.add('no-print-temp');
                    }
                });

                // 정확하게 Day당 4페이지 구조를 가정하여 인덱스 계산
                const startIndex = (selectedDay - 1) * 4;
                const endIndex = startIndex + 3;

                dataSheets.forEach((sheet, index) => {
                    if (index < startIndex || index > endIndex) {
                        sheet.classList.add('no-print-temp');
                        return;
                    }
                    const relIndex = index % 4;
                    if (choice === 'list' && relIndex !== 3) {
                        sheet.classList.add('no-print-temp');
                    }
                });
            }

            window.print();

            setTimeout(() => {
                allSheets.forEach(sheet => sheet.classList.remove('no-print-temp'));
                closePdfModal();
            }, 500);
        }
    </script>
</head>
<body>

<div class="top-controls no-print">
    <button onclick="openPdfModal()" class="btn-pdf-download"><i class="fa-solid fa-file-pdf"></i> PDF 저장하기</button>
</div>

<div id="pdfModal" class="modal-overlay no-print">
    <div class="modal-content">
        <h2><i class="fa-solid fa-print"></i> PDF 저장 옵션</h2>
        <p style="margin-bottom: 16px; font-size: 0.9rem; color: #6E767B;">다운로드할 범위를 선택해주세요.</p>
        <label class="print-option-label" style="background: #F8FAFC; border-color: #BFDBFE;">
            <input type="radio" name="printOption" value="all" checked>
            <span style="color: #2563EB;">전체 페이지 저장 (표지 포함)</span>
        </label>
        <div class="day-select-wrapper" style="margin-top: 12px;">
            <label for="daySelect">특정 Day 선택 (1~250)</label>
            <select id="daySelect" class="day-select"></select>
        </div>
        <label class="print-option-label"><input type="radio" name="printOption" value="day1"> 해당 Day 전체 (4페이지)</label>
        <label class="print-option-label"><input type="radio" name="printOption" value="list"> 10문장 리스트만 (마지막 1페이지)</label>
        <div class="modal-actions">
            <button class="btn-modal btn-cancel" onclick="closePdfModal()">취소</button>
            <button class="btn-modal btn-confirm" onclick="printSelectedPages()">저장하기</button>
        </div>
    </div>
</div>

<!-- ==========================================
     표지 (Cover 1, 2, 3)
=========================================== -->
<div class="sheet cover-sheet">
    <div class="bg-deco"></div>
    <div class="z-content" style="justify-content: center; align-items: center; text-align: center;">
        <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <h1 style="font-family: var(--font-kid); font-size: 3.5rem; color: var(--text-main); margin-bottom: 5px; line-height: 1.2;"><span style="color: var(--red-point);">청킹</span>으로 쉽게<br>영어말하기</h1>
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.4rem; color: var(--text-main); margin-bottom: 50px; letter-spacing: 0.5px;"><span style="color: var(--red-point);">Chunking</span>-Based Easy Speaking</h2>

            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 50px;">
                <img src="./img/exc_n1.png" onerror="this.src='https://api.iconify.design/fxemoji:rocket.svg'" style="width: 150px; height: 150px; object-fit: contain;" alt="character">
            </div>

            <p style="font-size: 1.25rem; font-weight: 700; color: var(--text-sub); letter-spacing: 1px;">이지윤 윤재우 윤보영 <span style="font-weight: 500; opacity: 0.8;">공저</span></p>
        </div>
        <div style="font-family: 'Pretendard', sans-serif; font-size: 2.8rem; font-weight: 900; color: var(--text-main); padding-bottom: 30px; letter-spacing: -2px;">선</div>
    </div>
</div>

<div class="sheet cover-sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <h2 style="font-family: 'Pretendard', sans-serif; font-weight: 800; font-size: 2.2rem; color: var(--text-main); text-align: center; margin: 10px 0 20px 0; letter-spacing: -1px;">공동저자</h2>

        <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: flex-start; margin-top: 40px;">
            <div class="author-section">
                <div class="img-box">
                    <img src="./img/au01.png" onerror="this.src='https://placehold.co/120x120/FFEFF2/FF7E96?text=이지윤'" alt="이지윤">
                </div>
                <div>
                    <h3>이지윤 <span style="font-size: 0.95rem; color: var(--text-sub); font-weight: 600; background: var(--primary-light); padding: 2px 10px; border-radius: 20px;">&lt;캐릭터&gt;</span></h3>
                    <ul>
                        <li>현) ㈜투게더7500 대표</li>
                        <li>현) 한국지역커뮤니티협회 발행인</li>
                        <li>전) 경향에듀케이션 부사장</li>
                        <li>뉴질랜드 피지컬칼리지 영어과 Certificate</li>
                        <li>평생교육사 2급 자격증</li>
                    </ul>
                </div>
            </div>

            <div class="author-section">
                <div class="img-box">
                    <img src="./img/au02.png" onerror="this.src='https://placehold.co/120x120/E0F2FE/3B82F6?text=윤재우'" alt="윤재우">
                </div>
                <div>
                    <h3>윤재우 <span style="font-size: 0.95rem; color: var(--text-sub); font-weight: 600; background: #EFF6FF; padding: 2px 10px; border-radius: 20px;">&lt;콘텐츠&gt;</span></h3>
                    <ul>
                        <li>현) PPSS(ㅍㅍㅅㅅ) 뉴미디어 대표</li>
                        <li>현) 한국인공지능빅데이터연구조합 부회장</li>
                        <li>전) 한국식품안전관리인증원 기획경영이사</li>
                        <li>전) 대통령비서실 디지털소통비서관실 선임행정관</li>
                        <li>‘청킹스피킹AUTO: 마법의 숫자7’ 저자 (2012년 11월, 선)</li>
                    </ul>
                </div>
            </div>

            <div class="author-section">
                <div class="img-box">
                    <img src="./img/au03.png" onerror="this.src='https://placehold.co/120x120/FEF3C7/D97706?text=윤보영'" alt="윤보영">
                </div>
                <div>
                    <h3>윤보영 <span style="font-size: 0.95rem; color: var(--text-sub); font-weight: 600; background: #FFFBEB; padding: 2px 10px; border-radius: 20px;">&lt;스피킹&gt;</span></h3>
                    <ul>
                        <li>현) 문화예술 스타트업 (주)샤콘느 대표</li>
                        <li>현) 키즈오페라 콘텐츠 크리에이터 겸 바이올리니스트</li>
                        <li>서울대 음대 학사 석사 졸업, 한양대 아동심리치료학과 박사 수료</li>
                        <li>키즈오페라 동화 애니메이션 10종(2022년 04월) 저자</li>
                        <li>키즈오페라 동화 10종(2022년 06월) 저자</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sheet cover-sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content" style="justify-content: center;">
        <h2 style="font-family: var(--font-kid); font-size: 1.8rem; color: var(--text-main); text-align: center; margin-bottom: 25px; line-height: 1.4;">
            드디어, 청킹<span style="font-family: 'Poppins';">Chunking</span>하다?<br>
            <span style="color: var(--primary);">자유롭고 유창하게 말하다 영어를!</span>
        </h2>

        <div style="font-size: 0.95rem; line-height: 1.6; color: var(--text-main); font-weight: 500;">
            <p style="margin-bottom: 15px; font-weight: 700; font-size: 1.05rem;">지금까지 이런 책은 없었습니다! 세상에 없었던 새로운 콘텐츠!<br>
                원어민과 같은 속도로 유창하게 영어 말하기를 250시간에 가능하게 하는<br>
                숨겨진 그 비법의 의미덩어리 청킹 기본표현을 완벽 정리하여 마침내 공개합니다!</p>

            <h3 style="color: var(--primary); margin: 20px 0 10px; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-star"></i> 제1단계 : 청킹 기본표현 반복숙달</h3>
            <p style="margin-bottom: 10px;">실제 일상생활에서 많이 사용하는, 아주 쉬운 초급동사 354개를 활용한,<br>
                직관적인 마법사 청킹 이미지와 함께하는 짧고 간단한 5,250개 의미덩어리 청킹 표현!</p>

            <p style="margin-bottom: 10px; background: #FFF; padding: 12px; border-radius: 12px; border: 1px solid #F0E4E7;">속도와 정확성이 생명인 영어 동시통역에서는, 단어 하나하나를 번역하는 게 아니라, 의미 있는 덩어리 청킹(chunking)으로 묶어서 통역하는 것이 아주 중요한 핵심 기술입니다. 즉 문장을 청킹으로 나눠서 각 덩어리를 하나의 단위로 통역합니다.</p>

            <p style="margin-bottom: 10px;">누구나 청킹학습법의 우수성을 말하고 권장하였지만, 지금 이 책처럼 이렇게 방대한 핵심적인 청킹표현을 체계적으로 정리한 자료는 없었습니다. <span style="color: var(--text-sub);">(꼬박 1년의 시간을 쏟아 부었습니다.)</span><br>
                쉬 단어의 의미덩어리 청킹이 반복 숙달되면 학습자는 실제 생활에서 청킹을 바로 꺼내 쓰기 때문에, 단기간에 영어말하기 능력이 문장 수준으로 급격히 올라갑니다.<br>
                놀랍도록 빨라진 영어 말하기 속도와 함께, 영어에 대한 자신감이 쑥쑥 높아지는 성장효과를 직접 경험하게 됩니다.</p>

            <h3 style="color: var(--primary); margin: 25px 0 10px; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-puzzle-piece"></i> 제2단계 : 청킹과 청킹을 투게더</h3>
            <p style="margin-bottom: 10px; font-weight: 700; color: var(--red-point);">청킹과 청킹을 투게더하면, 나의 영어나무가 자라요!<br>
                청킹과 청킹을 투게더하면, 나의 창의력도 함께 자라요!</p>

            <p style="margin-bottom: 10px;">1일 3개 청킹으로 to부정사, 동명사ing, 전치사+동명사구, 부사절, 등위절 까지 10개문장을,<br>
                250일 750개의 청킹으로 2,500개 문장을 만드는 know-how와 예시를 책에 담았습니다.<br>
                청킹과 청킹을 연결하여 문장을 늘리거나 줄이면서 자유롭게 만드는 know-how는 퍼즐을 맞추는 것처럼 아주 흥미롭기에 스스로 성취감을 느끼면서 꾸준히 이어갈 수 있습니다.<br>
                ‘마법사 청킹 투게더’와 함께 5,250개의 청킹을 자유롭게 연결하고 활용하는 문장 구성 능력은 물론 영어표현 창의성을 무궁무진하게 키워가기를 바랍니다!</p>

            <p style="margin-top: 20px; font-size: 0.8rem; color: #64748B; background: #F8FAFC; padding: 12px 16px; border-radius: 12px; border: 1px dashed #CBD5E1; line-height: 1.5;">
                * 웹기반 트레이닝 훈련용 프로그램(*지자체가 도입하면 주민 누구나 무료로 이용가능)은 5,250개 청킹의 1,2,3인칭과 의문문 부정문 명령문 등 36,750개 활용문장을 각 7번씩 반복하여 훈련함으로써 학습자가 자연스럽게 말하기 속도와 유창성을 향상하도록 합니다.
            </p>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 1 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 1 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 1</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
<!--            I_have_a_dream-->
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/I_have_a_dream.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I have a dream</h3><span>나는 가지다 꿈을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_a_dream.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a dream</h3><span>가지다 꿈을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_a_chance.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a chance</h3><span>가지다 기회를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_a_feeling.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a feeling</h3><span>가지다 감정을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_a_goal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a goal</h3><span>가지다 목표를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_a_hope.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a hope</h3><span>가지다 희망을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_an_idea.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have an idea</h3><span>가지다 아이디어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/01_have/have_a_wish.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a wish</h3><span>가지다 소망을</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 1 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 1</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/I_change_my_life.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I change my life</h3><span>나는 바꾸다 나의 생활을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_life.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the life</h3><span>바꾸다 생활을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the future</h3><span>바꾸다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_mind.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the mind</h3><span>바꾸다 마음을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_plan.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the plan</h3><span>바꾸다 계획을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_date.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the date</h3><span>바꾸다 날짜를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_place.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the place</h3><span>바꾸다 장소를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/02_change/change_the_time.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>change the time</h3><span>바꾸다 시간을</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 1 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 1</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/I_start_my_English_trip.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I start my English trip</h3><span>나는 시작하다 나의 영어 여행을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_trip.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the trip</h3><span>시작하다 여행을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_journey.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the journey</h3><span>시작하다 여행을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_class.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the class</h3><span>시작하다 수업을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_lesson.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the lesson</h3><span>시작하다 수업을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_day.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the day</h3><span>시작하다 하루를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_game.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the game</h3><span>시작하다 경기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day1/03_start/start_the_match.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>start the match</h3><span>시작하다 경기를</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 1 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 1</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a dream <span class="text-blue">to change my life.</span></div>
                        <div class="kor-sentence">나는 내 삶을 바꿀 꿈을 가지고 있어요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I start my English trip <span class="text-blue">to change my life.</span></div>
                        <div class="kor-sentence">나는 내 삶을 바꾸기 위해 영어 여행을 시작해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">Having a dream <span class="text-blue">helps me change my life.</span></div>
                        <div class="kor-sentence">꿈을 가지는 것은 내가 내 삶을 바꾸는 데 도움을 줘요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">Starting my English trip <span class="text-blue">helps me change my life.</span></div>
                        <div class="kor-sentence">영어 여행을 시작하는 것은 내가 내 삶을 바꾸는 데 도움을 줘요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a dream <span class="text-blue">about starting my English trip.</span></div>
                        <div class="kor-sentence">나는 영어 여행을 시작하는 것에 대한 꿈이 있어요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I change my life <span class="text-blue">after I have a dream.</span></div>
                        <div class="kor-sentence">나는 꿈을 가진 후에 내 삶을 바꿔요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I start my English trip <span class="text-blue">because I have a dream.</span></div>
                        <div class="kor-sentence">나는 꿈이 있기 때문에 영어 여행을 시작해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a dream, <span class="text-blue">so I change my life.</span></div>
                        <div class="kor-sentence">나는 꿈이 있어요, 그래서 내 삶을 바꿔요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I change my life, <span class="text-blue">and I start my English trip.</span></div>
                        <div class="kor-sentence">나는 내 삶을 바꿔요, 그리고 영어 여행을 시작해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I start my English trip, <span class="text-blue">and I have a dream.</span></div>
                        <div class="kor-sentence">나는 영어 여행을 시작해요, 그리고 꿈을 가져요.</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 2 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 2 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 2</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/I_learn_English.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I learn English</h3><span>나는 배우다 영어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_English.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn English</h3><span>배우다 영어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_Korean.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn Korean</h3><span>배우다 한국어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_Chinese.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn Chinese</h3><span>배우다 중국어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_Japanese.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn Japanese</h3><span>배우다 일본어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_French.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn French</h3><span>배우다 프랑스어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_German.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn German</h3><span>배우다 독일어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/04_learn/learn_Spanish.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>learn Spanish</h3><span>배우다 스페인어를</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 2 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 2</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/I_understand_the_problem.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I understand the problem</h3><span>나는 이해하다 문제를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_problem.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the problem</h3><span>이해하다 문제를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_difficulty.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the difficulty</h3><span>이해하다 어려움을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_worry.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the worry</h3><span>이해하다 걱정을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_issue.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the issue</h3><span>이해하다 이슈를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_topic.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the topic</h3><span>이해하다 주제를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_subject.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the subject</h3><span>이해하다 주제를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/05_understand/understand_the_lesson.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>understand the lesson</h3><span>이해하다 수업을</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 2 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 2</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/I_practice_speaking.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I practice speaking</h3><span>나는 연습하다 말하기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_speaking.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice speaking</h3><span>연습하다 말하기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_listening.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice listening</h3><span>연습하다 듣기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_reading.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice reading</h3><span>연습하다 읽기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_writing.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice writing</h3><span>연습하다 쓰기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_dancing.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice dancing</h3><span>연습하다 춤을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_singing.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice singing</h3><span>연습하다 노래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day2/06_practice/practice_drawing.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>practice drawing</h3><span>연습하다 그리기를</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 2 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 2</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I learn English <span class="text-blue">to understand the problem.</span></div>
                        <div class="kor-sentence">나는 문제를 이해하기 위해 영어를 배워요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I practice speaking <span class="text-blue">to learn English.</span></div>
                        <div class="kor-sentence">나는 영어를 배우기 위해 말하기를 연습해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">Learning English <span class="text-blue">helps me understand the problem.</span></div>
                        <div class="kor-sentence">영어를 배우는 것은 내가 문제를 이해하는 데 도움을 줘요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">Practicing speaking <span class="text-blue">helps me understand the problem.</span></div>
                        <div class="kor-sentence">말하기를 연습하는 것은 내가 문제를 이해하는 데 도움을 줘요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I learn English <span class="text-blue">by practicing speaking.</span></div>
                        <div class="kor-sentence">나는 말하기를 연습함으로써 영어를 배워요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I practice speaking <span class="text-blue">when I understand the problem.</span></div>
                        <div class="kor-sentence">나는 문제를 이해할 때 말하기를 연습해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I learn English <span class="text-blue">after I understand the problem.</span></div>
                        <div class="kor-sentence">나는 문제를 이해한 후에 영어를 배워요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I learn English, <span class="text-blue">and I practice speaking.</span></div>
                        <div class="kor-sentence">나는 영어를 배워요, 그리고 말하기를 연습해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I understand the problem, <span class="text-blue">and I learn English.</span></div>
                        <div class="kor-sentence">나는 문제를 이해해요, 그리고 영어를 배워요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I practice speaking, <span class="text-blue">and I understand the problem.</span></div>
                        <div class="kor-sentence">나는 말하기를 연습해요, 그리고 문제를 이해해요.</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 3 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 3 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 3</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/I_tell_a_secret.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I tell a secret</h3><span>나는 말하다 비밀을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_secret.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a secret</h3><span>말하다 비밀을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_story.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a story</h3><span>말하다 이야기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_tale.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a tale</h3><span>말하다 이야기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_joke.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a joke</h3><span>말하다 농담을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_lie.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a lie</h3><span>말하다 거짓말을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_difference.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a difference</h3><span>말하다 차이를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/07_tell/tell_a_reason.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tell a reason</h3><span>말하다 이유를</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 3 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 3</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/I_write_the_chunking_list.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I write the chunking list</h3><span>나는 쓰다 청킹목록을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_list.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the list</h3><span>쓰다 목록을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_essay.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the essay</h3><span>쓰다 에세이를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_email.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the email</h3><span>쓰다 이메일을</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_letter.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the letter</h3><span>쓰다 편지를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_message.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the message</h3><span>쓰다 메시지를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_report.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the report</h3><span>쓰다 보고서를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/08_write/write_the_story.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the story</h3><span>쓰다 이야기를</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 3 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 3</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/I_read_aloud.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I read aloud</h3><span>나는 읽다 큰소리로</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_aloud.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read aloud</h3><span>읽다 큰소리로</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_attentively.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read attentively</h3><span>읽다 신중하게</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_clearly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read clearly</h3><span>읽다 명확하게</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_carefully.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read carefully</h3><span>읽다 주의 깊게</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_quietly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read quietly</h3><span>읽다 조용히</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_quickly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read quickly</h3><span>읽다 빠르게</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day3/09_read/read_slowly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read slowly</h3><span>읽다 천천히</span></div></div></div>

            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 단 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 3 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 3</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I write the chunking list <span class="text-blue">to tell a secret.</span></div>
                        <div class="kor-sentence">나는 비밀을 말하기 위해 청킹 리스트를 써요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I write the chunking list <span class="text-blue">to read aloud.</span></div>
                        <div class="kor-sentence">나는 큰 소리로 읽기 위해 청킹 리스트를 써요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">Reading aloud <span class="text-blue">helps me tell a secret.</span></div>
                        <div class="kor-sentence">큰 소리로 읽는 것은 내가 비밀을 말하는 데 도움을 줘요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I tell a secret <span class="text-blue">about writing the chunking list.</span></div>
                        <div class="kor-sentence">나는 청킹 리스트를 쓰는 것에 대한 비밀을 말해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read aloud <span class="text-blue">when I tell a secret.</span></div>
                        <div class="kor-sentence">나는 비밀을 말할 때 큰 소리로 읽어요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read aloud <span class="text-blue">after I write the chunking list.</span></div>
                        <div class="kor-sentence">나는 청킹 리스트를 쓴 후에 큰 소리로 읽어요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I tell a secret <span class="text-blue">after I write the chunking list.</span></div>
                        <div class="kor-sentence">나는 청킹 리스트를 쓴 후에 비밀을 말해요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I tell a secret, <span class="text-blue">and I write the chunking list.</span></div>
                        <div class="kor-sentence">나는 비밀을 말해요, 그리고 청킹 리스트를 써요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I write the chunking list, <span class="text-blue">and I read aloud.</span></div>
                        <div class="kor-sentence">나는 청킹 리스트를 써요, 그리고 큰 소리로 읽어요.</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read aloud, <span class="text-blue">and I tell a secret.</span></div>
                        <div class="kor-sentence">나는 큰 소리로 읽어요, 그리고 비밀을 말해요.</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 4 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 4 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 4</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/I_repeat_chunking.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I repeat chunking</h3><span>나는 반복하다 청킹를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_chunking.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat chunking</h3><span>반복하다 청킹를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_the_content.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat the content</h3><span>반복하다 content를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_the_message.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat the message</h3><span>반복하다 메시지를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_the_point.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat the point</h3><span>반복하다 포인트를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_the_sentence.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat the sentence</h3><span>반복하다 sentence를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_the_story.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat the story</h3><span>반복하다 이야기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/10_repeat/repeat_the_word.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>repeat the word</h3><span>반복하다 word를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 4 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 4</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/I_use_easy_words.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I use easy words</h3><span>나는 사용하다 easy words를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_body.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the body</h3><span>사용하다 몸를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_hands.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the hands</h3><span>사용하다 hands를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_head.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the head</h3><span>사용하다 head를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_name.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the name</h3><span>사용하다 name를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_time.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the time</h3><span>사용하다 시간를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_voice.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the voice</h3><span>사용하다 목소리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/11_use/use_the_words.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the words</h3><span>사용하다 words를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 4 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 4</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/I_think_in_English.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I think in English</h3><span>나는 생각하다 영어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_Chinese.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in Chinese</h3><span>생각하다 중국어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_English.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in English</h3><span>생각하다 영어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_French.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in French</h3><span>생각하다 프랑스어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_German.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in German</h3><span>생각하다 독일어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_Japanese.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in Japanese</h3><span>생각하다 일본어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_Korean.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in Korean</h3><span>생각하다 한국어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day4/12_think_in/think_in_Spanish.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think in Spanish</h3><span>생각하다 스페인어를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 4 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 4</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I repeat chunking to use.</div>
                        <div class="kor-sentence">나는 반복하다 청킹를 (to use)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think in English to use.</div>
                        <div class="kor-sentence">나는 생각하다 영어를 (to use)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I repeat chunking-ing helps me use.</div>
                        <div class="kor-sentence">(나는 반복하다 청킹를이) 나는 사용하다 easy words를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think in English-ing helps me use.</div>
                        <div class="kor-sentence">(나는 생각하다 영어를이) 나는 사용하다 easy words를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I repeat chunking about think in.</div>
                        <div class="kor-sentence">나는 반복하다 청킹를 (think in에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I use easy words after I repeat chunking.</div>
                        <div class="kor-sentence">나는 반복하다 청킹를 후에 나는 사용하다 easy words를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think in English because I repeat chunking.</div>
                        <div class="kor-sentence">나는 반복하다 청킹를 때문에 나는 생각하다 영어를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I repeat chunking, so I use easy words.</div>
                        <div class="kor-sentence">나는 반복하다 청킹를, 그래서 나는 사용하다 easy words를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I use easy words, and I think in English.</div>
                        <div class="kor-sentence">나는 사용하다 easy words를, 그리고 나는 생각하다 영어를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think in English, and I repeat chunking.</div>
                        <div class="kor-sentence">나는 생각하다 영어를, 그리고 나는 반복하다 청킹를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 5 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 5 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 5</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/I_train_chunking.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I train chunking</h3><span>나는 훈련하다 청킹를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_chunking.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train chunking</h3><span>훈련하다 청킹를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_the_arms.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train the arms</h3><span>훈련하다 arms를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_the_body.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train the body</h3><span>훈련하다 몸를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_the_brain.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train the brain</h3><span>훈련하다 뇌를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_the_memory.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train the memory</h3><span>훈련하다 memory를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_the_mind.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train the mind</h3><span>훈련하다 마음를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/13_train/train_the_muscle.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>train the muscle</h3><span>훈련하다 muscle를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 5 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 5</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/I_plant_my_English_tree.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I plant my English tree</h3><span>나는 심다 English tree를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_crop.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the crop</h3><span>심다 crop를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_flower.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the flower</h3><span>심다 flower를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_grass.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the grass</h3><span>심다 잔디를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_sapling.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the sapling</h3><span>심다 sapling를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_seed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the seed</h3><span>심다 seed를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_seedling.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the seedling</h3><span>심다 seedling를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/14_plant/plant_the_tree.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plant the tree</h3><span>심다 나무를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 5 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 5</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/I_keep_active.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I keep active</h3><span>나는 유지하다 active를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_active.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep active</h3><span>유지하다 active를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_confident.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep confident</h3><span>유지하다 confident를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_patient.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep patient</h3><span>유지하다 patient를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_positive.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep positive</h3><span>유지하다 positive를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_safe.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep safe</h3><span>유지하다 safe를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_steady.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep steady</h3><span>유지하다 꾸준히를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day5/15_keep/keep_strong.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep strong</h3><span>유지하다 강하게를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 5 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 5</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I train chunking to plant.</div>
                        <div class="kor-sentence">나는 훈련하다 청킹를 (to plant)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep active to plant.</div>
                        <div class="kor-sentence">나는 유지하다 active를 (to plant)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I train chunking-ing helps me plant.</div>
                        <div class="kor-sentence">(나는 훈련하다 청킹를이) 나는 심다 English tree를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep active-ing helps me plant.</div>
                        <div class="kor-sentence">(나는 유지하다 active를이) 나는 심다 English tree를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I train chunking about keep.</div>
                        <div class="kor-sentence">나는 훈련하다 청킹를 (keep에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I plant my English tree after I train chunking.</div>
                        <div class="kor-sentence">나는 훈련하다 청킹를 후에 나는 심다 English tree를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep active because I train chunking.</div>
                        <div class="kor-sentence">나는 훈련하다 청킹를 때문에 나는 유지하다 active를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I train chunking, so I plant my English tree.</div>
                        <div class="kor-sentence">나는 훈련하다 청킹를, 그래서 나는 심다 English tree를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I plant my English tree, and I keep active.</div>
                        <div class="kor-sentence">나는 심다 English tree를, 그리고 나는 유지하다 active를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep active, and I train chunking.</div>
                        <div class="kor-sentence">나는 유지하다 active를, 그리고 나는 훈련하다 청킹를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 6 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 6 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 6</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/I_spend_the_time.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I spend the time</h3><span>나는 쓰다 시간를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_afternoon.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the afternoon</h3><span>쓰다 afternoon를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_day.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the day</h3><span>쓰다 하루를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_evening.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the evening</h3><span>쓰다 evening를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_hour.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the hour</h3><span>쓰다 시간를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_morning.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the morning</h3><span>쓰다 아침를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_night.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the night</h3><span>쓰다 night를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/16_spend/spend_the_time.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spend the time</h3><span>쓰다 시간를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 6 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 6</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/I_watch_English_cartoons.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I watch English cartoons</h3><span>나는 보다 English cartoons를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_news.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the news</h3><span>보다 뉴스를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_cartoon.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the cartoon</h3><span>보다 cartoon를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_drama.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the drama</h3><span>보다 drama를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_film.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the film</h3><span>보다 film를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_movie.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the movie</h3><span>보다 movie를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_soap_opera.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the soap opera</h3><span>보다 soap opera를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/17_watch/watch_the_video.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>watch the video</h3><span>보다 video를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 6 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 6</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/I_catch_new_words.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I catch new words</h3><span>나는 잡다 new words를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_joke.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the joke</h3><span>잡다 농담를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_meaning.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the meaning</h3><span>잡다 meaning를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_name.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the name</h3><span>잡다 name를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_number.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the number</h3><span>잡다 number를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_point.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the point</h3><span>잡다 포인트를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_question.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the question</h3><span>잡다 question를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day6/18_catch/catch_the_words.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>catch the words</h3><span>잡다 words를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 6 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 6</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I spend the time to watch.</div>
                        <div class="kor-sentence">나는 쓰다 시간를 (to watch)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I catch new words to watch.</div>
                        <div class="kor-sentence">나는 잡다 new words를 (to watch)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I spend the time-ing helps me watch.</div>
                        <div class="kor-sentence">(나는 쓰다 시간를이) 나는 보다 English cartoons를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I catch new words-ing helps me watch.</div>
                        <div class="kor-sentence">(나는 잡다 new words를이) 나는 보다 English cartoons를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I spend the time about catch.</div>
                        <div class="kor-sentence">나는 쓰다 시간를 (catch에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I watch English cartoons after I spend the time.</div>
                        <div class="kor-sentence">나는 쓰다 시간를 후에 나는 보다 English cartoons를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I catch new words because I spend the time.</div>
                        <div class="kor-sentence">나는 쓰다 시간를 때문에 나는 잡다 new words를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I spend the time, so I watch English cartoons.</div>
                        <div class="kor-sentence">나는 쓰다 시간를, 그래서 나는 보다 English cartoons를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I watch English cartoons, and I catch new words.</div>
                        <div class="kor-sentence">나는 보다 English cartoons를, 그리고 나는 잡다 new words를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I catch new words, and I spend the time.</div>
                        <div class="kor-sentence">나는 잡다 new words를, 그리고 나는 쓰다 시간를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 7 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 7 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 7</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/I_think_of_my_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I think of my future</h3><span>나는 생각하다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_end.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the end</h3><span>생각하다 end를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the future</h3><span>생각하다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_idea.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the idea</h3><span>생각하다 아이디어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_past.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the past</h3><span>생각하다 past를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_risk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the risk</h3><span>생각하다 risk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_title.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the title</h3><span>생각하다 title를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/19_think_of/think_of_the_word.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>think of the word</h3><span>생각하다 word를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 7 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 7</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/I_plan_my_day..png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I plan my day.</h3><span>나는 계획하다 day.를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_day.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the day</h3><span>계획하다 하루를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the future</h3><span>계획하다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_holiday.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the holiday</h3><span>계획하다 holiday를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_project.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the project</h3><span>계획하다 project를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_schedule.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the schedule</h3><span>계획하다 schedule를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_step.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the step</h3><span>계획하다 step를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/20_plan/plan_the_timetable.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>plan the timetable</h3><span>계획하다 timetable를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 7 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 7</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/I_read_English_books.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I read English books</h3><span>나는 읽다 English books를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_book.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the book</h3><span>읽다 book를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_chapter.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the chapter</h3><span>읽다 chapter를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_novel.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the novel</h3><span>읽다 novel를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_page.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the page</h3><span>읽다 page를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_script.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the script</h3><span>읽다 script를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_summary.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the summary</h3><span>읽다 summary를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day7/21_read/read_the_text.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>read the text</h3><span>읽다 text를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 7 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 7</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think of my future to plan.</div>
                        <div class="kor-sentence">나는 생각하다 미래를 (to plan)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read English books to plan.</div>
                        <div class="kor-sentence">나는 읽다 English books를 (to plan)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think of my future-ing helps me plan.</div>
                        <div class="kor-sentence">(나는 생각하다 미래를이) 나는 계획하다 day.를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read English books-ing helps me plan.</div>
                        <div class="kor-sentence">(나는 읽다 English books를이) 나는 계획하다 day.를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think of my future about read.</div>
                        <div class="kor-sentence">나는 생각하다 미래를 (read에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I plan my day. after I think of my future.</div>
                        <div class="kor-sentence">나는 생각하다 미래를 후에 나는 계획하다 day.를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read English books because I think of my future.</div>
                        <div class="kor-sentence">나는 생각하다 미래를 때문에 나는 읽다 English books를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I think of my future, so I plan my day..</div>
                        <div class="kor-sentence">나는 생각하다 미래를, 그래서 나는 계획하다 day.를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I plan my day., and I read English books.</div>
                        <div class="kor-sentence">나는 계획하다 day.를, 그리고 나는 읽다 English books를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I read English books, and I think of my future.</div>
                        <div class="kor-sentence">나는 읽다 English books를, 그리고 나는 생각하다 미래를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 8 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 8 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 8</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/I_face_the_challenge.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I face the challenge</h3><span>나는 마주하다 challenge를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_challenge.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the challenge</h3><span>마주하다 challenge를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_fact.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the fact</h3><span>마주하다 fact를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the future</h3><span>마주하다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_reality.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the reality</h3><span>마주하다 reality를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_situation.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the situation</h3><span>마주하다 situation를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_truth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the truth</h3><span>마주하다 truth를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/22_face/face_the_world.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the world</h3><span>마주하다 world를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 8 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 8</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/I_write_the_diary.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I write the diary</h3><span>나는 쓰다 diary를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_article.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the article</h3><span>쓰다 article를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_book.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the book</h3><span>쓰다 book를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_diary.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the diary</h3><span>쓰다 diary를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_novel.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the novel</h3><span>쓰다 novel를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_poem.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the poem</h3><span>쓰다 poem를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_scenario.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the scenario</h3><span>쓰다 scenario를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/23_write/write_the_song.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>write the song</h3><span>쓰다 song를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 8 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 8</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/I_keep_up_the_good_work.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I keep up the good work</h3><span>나는 유지하다 good work를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_courage.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the courage</h3><span>유지하다 courage를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_good_work.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the good work</h3><span>유지하다 good work를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_morale.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the morale</h3><span>유지하다 morale를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_pace.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the pace</h3><span>유지하다 pace를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_price.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the price</h3><span>유지하다 price를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_spirit.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the spirit</h3><span>유지하다 spirit를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day8/24_keep_up/keep_up_the_steam.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>keep up the steam</h3><span>유지하다 steam를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 8 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 8</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the challenge to write.</div>
                        <div class="kor-sentence">나는 마주하다 challenge를 (to write)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep up the good work to write.</div>
                        <div class="kor-sentence">나는 유지하다 good work를 (to write)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the challenge-ing helps me write.</div>
                        <div class="kor-sentence">(나는 마주하다 challenge를이) 나는 쓰다 diary를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep up the good work-ing helps me write.</div>
                        <div class="kor-sentence">(나는 유지하다 good work를이) 나는 쓰다 diary를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the challenge about keep up.</div>
                        <div class="kor-sentence">나는 마주하다 challenge를 (keep up에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I write the diary after I face the challenge.</div>
                        <div class="kor-sentence">나는 마주하다 challenge를 후에 나는 쓰다 diary를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep up the good work because I face the challenge.</div>
                        <div class="kor-sentence">나는 마주하다 challenge를 때문에 나는 유지하다 good work를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the challenge, so I write the diary.</div>
                        <div class="kor-sentence">나는 마주하다 challenge를, 그래서 나는 쓰다 diary를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I write the diary, and I keep up the good work.</div>
                        <div class="kor-sentence">나는 쓰다 diary를, 그리고 나는 유지하다 good work를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I keep up the good work, and I face the challenge.</div>
                        <div class="kor-sentence">나는 유지하다 good work를, 그리고 나는 마주하다 challenge를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 9 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 9 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 9</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/I_improve_my_English.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I improve my English</h3><span>나는 향상시키다 영어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_English.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve English</h3><span>향상시키다 영어를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_the_image.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve the image</h3><span>향상시키다 image를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_the_quality.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve the quality</h3><span>향상시키다 quality를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_the_service.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve the service</h3><span>향상시키다 service를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_the_situation.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve the situation</h3><span>향상시키다 situation를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_the_standard.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve the standard</h3><span>향상시키다 standard를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/25_improve/improve_the_system.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>improve the system</h3><span>향상시키다 system를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 9 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 9</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/I_visit_the_foreign_country.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I visit the foreign country</h3><span>나는 방문하다 foreign country를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_city.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the city</h3><span>방문하다 city를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_country.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the country</h3><span>방문하다 country를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_gallery.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the gallery</h3><span>방문하다 gallery를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_library.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the library</h3><span>방문하다 library를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_museum.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the museum</h3><span>방문하다 museum를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_palace.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the palace</h3><span>방문하다 palace를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/26_visit/visit_the_park.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>visit the park</h3><span>방문하다 park를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 9 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 9</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/I_build_up_my_confidence.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I build up my confidence</h3><span>나는 쌓다 confidence를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_body.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the body</h3><span>쌓다 몸를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_confidence.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the confidence</h3><span>쌓다 confidence를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_friendship.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the friendship</h3><span>쌓다 friendship를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_muscles.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the muscles</h3><span>쌓다 muscles를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_shoulders.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the shoulders</h3><span>쌓다 shoulders를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_story.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the story</h3><span>쌓다 이야기를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day9/27_build_up/build_up_the_teamwork.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>build up the teamwork</h3><span>쌓다 teamwork를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 9 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 9</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I improve my English to visit.</div>
                        <div class="kor-sentence">나는 향상시키다 영어를 (to visit)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I build up my confidence to visit.</div>
                        <div class="kor-sentence">나는 쌓다 confidence를 (to visit)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I improve my English-ing helps me visit.</div>
                        <div class="kor-sentence">(나는 향상시키다 영어를이) 나는 방문하다 foreign country를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I build up my confidence-ing helps me visit.</div>
                        <div class="kor-sentence">(나는 쌓다 confidence를이) 나는 방문하다 foreign country를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I improve my English about build up.</div>
                        <div class="kor-sentence">나는 향상시키다 영어를 (build up에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I visit the foreign country after I improve my English.</div>
                        <div class="kor-sentence">나는 향상시키다 영어를 후에 나는 방문하다 foreign country를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I build up my confidence because I improve my English.</div>
                        <div class="kor-sentence">나는 향상시키다 영어를 때문에 나는 쌓다 confidence를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I improve my English, so I visit the foreign country.</div>
                        <div class="kor-sentence">나는 향상시키다 영어를, 그래서 나는 방문하다 foreign country를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I visit the foreign country, and I build up my confidence.</div>
                        <div class="kor-sentence">나는 방문하다 foreign country를, 그리고 나는 쌓다 confidence를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I build up my confidence, and I improve my English.</div>
                        <div class="kor-sentence">나는 쌓다 confidence를, 그리고 나는 향상시키다 영어를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 10 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 10 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 10</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/I_pass_my_English_test.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I pass my English test</h3><span>나는 통과하다 English test를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_audition.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the audition</h3><span>통과하다 audition를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_course.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the course</h3><span>통과하다 course를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_exam.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the exam</h3><span>통과하다 exam를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_interview.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the interview</h3><span>통과하다 interview를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_process.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the process</h3><span>통과하다 process를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_standard.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the standard</h3><span>통과하다 standard를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/28_pass/pass_the_test.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the test</h3><span>통과하다 test를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 10 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 10</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/I_achieve_my_goal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I achieve my goal</h3><span>나는 성취하다 목표를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_dream.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the dream</h3><span>성취하다 꿈를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_goal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the goal</h3><span>성취하다 목표를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_grade.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the grade</h3><span>성취하다 grade를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_hope.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the hope</h3><span>성취하다 희망를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_progress.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the progress</h3><span>성취하다 progress를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_success.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the success</h3><span>성취하다 success를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/29_achieve/achieve_the_victory.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>achieve the victory</h3><span>성취하다 victory를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 10 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 10</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/I_am_proud_of_myself.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I am proud of myself</h3><span>나는 되다 proud of myself를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_active.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be active</h3><span>되다 active를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_ashamed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be ashamed</h3><span>되다 ashamed를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_negative.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be negative</h3><span>되다 negative를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_passive.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be passive</h3><span>되다 passive를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_positive.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be positive</h3><span>되다 positive를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_proud.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be proud</h3><span>되다 proud를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day10/30_be/be_shameful.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be shameful</h3><span>되다 shameful를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 10 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 10</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass my English test to achieve.</div>
                        <div class="kor-sentence">나는 통과하다 English test를 (to achieve)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am proud of myself to achieve.</div>
                        <div class="kor-sentence">나는 되다 proud of myself를 (to achieve)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass my English test-ing helps me achieve.</div>
                        <div class="kor-sentence">(나는 통과하다 English test를이) 나는 성취하다 목표를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am proud of myself-ing helps me achieve.</div>
                        <div class="kor-sentence">(나는 되다 proud of myself를이) 나는 성취하다 목표를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass my English test about be.</div>
                        <div class="kor-sentence">나는 통과하다 English test를 (be에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I achieve my goal after I pass my English test.</div>
                        <div class="kor-sentence">나는 통과하다 English test를 후에 나는 성취하다 목표를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am proud of myself because I pass my English test.</div>
                        <div class="kor-sentence">나는 통과하다 English test를 때문에 나는 되다 proud of myself를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass my English test, so I achieve my goal.</div>
                        <div class="kor-sentence">나는 통과하다 English test를, 그래서 나는 성취하다 목표를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I achieve my goal, and I am proud of myself.</div>
                        <div class="kor-sentence">나는 성취하다 목표를, 그리고 나는 되다 proud of myself를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am proud of myself, and I pass my English test.</div>
                        <div class="kor-sentence">나는 되다 proud of myself를, 그리고 나는 통과하다 English test를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 11 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 11 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 11</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/I_turn_off_the_alarm.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I turn off the alarm</h3><span>나는 끄다 alarm를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_alarm.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the alarm</h3><span>끄다 alarm를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_engine.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the engine</h3><span>끄다 engine를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_fan.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the fan</h3><span>끄다 fan를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_gas.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the gas</h3><span>끄다 gas를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_heater.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the heater</h3><span>끄다 heater를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_power.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the power</h3><span>끄다 power를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/31_turn_off/turn_off_the_switch.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the switch</h3><span>끄다 switch를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 11 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 11</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/I_wake_up_early.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I wake up early</h3><span>나는 일어나다 일찍를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_early.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up early</h3><span>일어나다 일찍를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_easily.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up easily</h3><span>일어나다 easily를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_late.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up late</h3><span>일어나다 late를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_on_time.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up on time</h3><span>일어나다 on time를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_quickly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up quickly</h3><span>일어나다 빠르게를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_quietly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up quietly</h3><span>일어나다 조용히를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/32_wake_up/wake_up_slowly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wake up slowly</h3><span>일어나다 천천히를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 11 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 11</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/I_open_my_eyes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I open my eyes</h3><span>나는 열다 eyes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_a_bank_account.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open a bank account</h3><span>열다 bank account를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_the_book.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open the book</h3><span>열다 book를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_the_eyes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open the eyes</h3><span>열다 eyes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_the_map.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open the map</h3><span>열다 map를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_the_mouth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open the mouth</h3><span>열다 mouth를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_the_page.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open the page</h3><span>열다 page를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day11/33_open/open_the_paper.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>open the paper</h3><span>열다 paper를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 11 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 11</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the alarm to wake up.</div>
                        <div class="kor-sentence">나는 끄다 alarm를 (to wake up)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I open my eyes to wake up.</div>
                        <div class="kor-sentence">나는 열다 eyes를 (to wake up)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the alarm-ing helps me wake up.</div>
                        <div class="kor-sentence">(나는 끄다 alarm를이) 나는 일어나다 일찍를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I open my eyes-ing helps me wake up.</div>
                        <div class="kor-sentence">(나는 열다 eyes를이) 나는 일어나다 일찍를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the alarm about open.</div>
                        <div class="kor-sentence">나는 끄다 alarm를 (open에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I wake up early after I turn off the alarm.</div>
                        <div class="kor-sentence">나는 끄다 alarm를 후에 나는 일어나다 일찍를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I open my eyes because I turn off the alarm.</div>
                        <div class="kor-sentence">나는 끄다 alarm를 때문에 나는 열다 eyes를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the alarm, so I wake up early.</div>
                        <div class="kor-sentence">나는 끄다 alarm를, 그래서 나는 일어나다 일찍를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I wake up early, and I open my eyes.</div>
                        <div class="kor-sentence">나는 일어나다 일찍를, 그리고 나는 열다 eyes를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I open my eyes, and I turn off the alarm.</div>
                        <div class="kor-sentence">나는 열다 eyes를, 그리고 나는 끄다 alarm를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 12 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 12 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 12</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/I_look_around_my_room.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I look around my room</h3><span>나는 둘러보다 room를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_city.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the city</h3><span>둘러보다 city를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_house.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the house</h3><span>둘러보다 house를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_museum.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the museum</h3><span>둘러보다 museum를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_room.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the room</h3><span>둘러보다 room를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_school.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the school</h3><span>둘러보다 school를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_shop.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the shop</h3><span>둘러보다 shop를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/34_look_around/look_around_the_store.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look around the store</h3><span>둘러보다 store를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 12 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 12</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/I_yawn_widely.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I yawn widely</h3><span>나는 하품하다 widely를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_deeply.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn deeply</h3><span>하품하다 deeply를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_loudly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn loudly</h3><span>하품하다 loudly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_quickly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn quickly</h3><span>하품하다 빠르게를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_quietly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn quietly</h3><span>하품하다 조용히를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_repeatedly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn repeatedly</h3><span>하품하다 repeatedly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_slowly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn slowly</h3><span>하품하다 천천히를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/35_yawn/yawn_widely.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>yawn widely</h3><span>하품하다 widely를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 12 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 12</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/I_make_my_bed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I make my bed</h3><span>나는 만들다 침대를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_a_meal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make a meal</h3><span>만들다 meal를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_breakfast.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make breakfast</h3><span>만들다 breakfast를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_dinner.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make dinner</h3><span>만들다 dinner를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_food.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make food</h3><span>만들다 food를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_lunch.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make lunch</h3><span>만들다 lunch를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_supper.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make supper</h3><span>만들다 supper를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day12/36_make/make_the_bed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>make the bed</h3><span>만들다 침대를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 12 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 12</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look around my room to yawn.</div>
                        <div class="kor-sentence">나는 둘러보다 room를 (to yawn)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I make my bed to yawn.</div>
                        <div class="kor-sentence">나는 만들다 침대를 (to yawn)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look around my room-ing helps me yawn.</div>
                        <div class="kor-sentence">(나는 둘러보다 room를이) 나는 하품하다 widely를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I make my bed-ing helps me yawn.</div>
                        <div class="kor-sentence">(나는 만들다 침대를이) 나는 하품하다 widely를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look around my room about make.</div>
                        <div class="kor-sentence">나는 둘러보다 room를 (make에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I yawn widely after I look around my room.</div>
                        <div class="kor-sentence">나는 둘러보다 room를 후에 나는 하품하다 widely를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I make my bed because I look around my room.</div>
                        <div class="kor-sentence">나는 둘러보다 room를 때문에 나는 만들다 침대를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look around my room, so I yawn widely.</div>
                        <div class="kor-sentence">나는 둘러보다 room를, 그래서 나는 하품하다 widely를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I yawn widely, and I make my bed.</div>
                        <div class="kor-sentence">나는 하품하다 widely를, 그리고 나는 만들다 침대를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I make my bed, and I look around my room.</div>
                        <div class="kor-sentence">나는 만들다 침대를, 그리고 나는 둘러보다 room를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 13 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 13 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 13</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/I_greet_the_morning.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I greet the morning</h3><span>나는 인사하다 아침를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_New_Year.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the New Year</h3><span>인사하다 New Year를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_customer.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the customer</h3><span>인사하다 customer를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_day.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the day</h3><span>인사하다 하루를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_fan.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the fan</h3><span>인사하다 fan를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_guest.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the guest</h3><span>인사하다 guest를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_morning.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the morning</h3><span>인사하다 아침를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/37_greet/greet_the_spring.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>greet the spring</h3><span>인사하다 spring를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 13 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 13</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/I_feel_very_happy.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I feel very happy</h3><span>나는 느끼다 very happy를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_comfortable.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel comfortable</h3><span>느끼다 comfortable를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_free.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel free</h3><span>느끼다 free를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_fresh.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel fresh</h3><span>느끼다 fresh를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_good.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel good</h3><span>느끼다 good를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_great.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel great</h3><span>느끼다 훌륭한를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_happy.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel happy</h3><span>느끼다 happy를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/38_feel/feel_lucky.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>feel lucky</h3><span>느끼다 lucky를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 13 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 13</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/I_say_Good_morning.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I say Good morning</h3><span>나는 말하다 Good morning를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_good_afternoon.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say good afternoon</h3><span>말하다 good afternoon를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_good_evening.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say good evening</h3><span>말하다 good evening를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_good_job.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say good job</h3><span>말하다 good job를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_good_luck.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say good luck</h3><span>말하다 good luck를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_good_morning.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say good morning</h3><span>말하다 good morning를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_good_night.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say good night</h3><span>말하다 good night를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day13/39_say/say_goodbye.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>say goodbye</h3><span>말하다 goodbye를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 13 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 13</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I greet the morning to feel.</div>
                        <div class="kor-sentence">나는 인사하다 아침를 (to feel)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I say Good morning to feel.</div>
                        <div class="kor-sentence">나는 말하다 Good morning를 (to feel)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I greet the morning-ing helps me feel.</div>
                        <div class="kor-sentence">(나는 인사하다 아침를이) 나는 느끼다 very happy를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I say Good morning-ing helps me feel.</div>
                        <div class="kor-sentence">(나는 말하다 Good morning를이) 나는 느끼다 very happy를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I greet the morning about say.</div>
                        <div class="kor-sentence">나는 인사하다 아침를 (say에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I feel very happy after I greet the morning.</div>
                        <div class="kor-sentence">나는 인사하다 아침를 후에 나는 느끼다 very happy를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I say Good morning because I greet the morning.</div>
                        <div class="kor-sentence">나는 인사하다 아침를 때문에 나는 말하다 Good morning를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I greet the morning, so I feel very happy.</div>
                        <div class="kor-sentence">나는 인사하다 아침를, 그래서 나는 느끼다 very happy를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I feel very happy, and I say Good morning.</div>
                        <div class="kor-sentence">나는 느끼다 very happy를, 그리고 나는 말하다 Good morning를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I say Good morning, and I greet the morning.</div>
                        <div class="kor-sentence">나는 말하다 Good morning를, 그리고 나는 인사하다 아침를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 14 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 14 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 14</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/I_sit_on_the_floor.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I sit on the floor</h3><span>나는 앉다 floor를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_bed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the bed</h3><span>앉다 침대를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_chair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the chair</h3><span>앉다 chair를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_couch.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the couch</h3><span>앉다 couch를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_floor.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the floor</h3><span>앉다 floor를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_knee.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the knee</h3><span>앉다 knee를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_lap.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the lap</h3><span>앉다 lap를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/40_sit_on/sit_on_the_sofa.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>sit on the sofa</h3><span>앉다 sofa를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 14 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 14</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/I_stay_calm.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I stay calm</h3><span>나는 머물다 calm를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_calm.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay calm</h3><span>머물다 calm를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_cool.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay cool</h3><span>머물다 멋진를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_fit.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay fit</h3><span>머물다 fit를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_healthy.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay healthy</h3><span>머물다 healthy를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_quiet.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay quiet</h3><span>머물다 quiet를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_slim.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay slim</h3><span>머물다 slim를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/41_stay/stay_still.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>stay still</h3><span>머물다 still를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 14 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 14</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/I_do_exercise.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I do exercise</h3><span>나는 하다 exercise를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_exercise.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do exercise</h3><span>하다 exercise를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_pullups.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do pullups</h3><span>하다 pullups를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_pushups.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do pushups</h3><span>하다 pushups를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_situps.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do situps</h3><span>하다 situps를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_squats.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do squats</h3><span>하다 squats를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_warmup.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do warmup</h3><span>하다 warmup를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day14/42_do/do_yoga.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>do yoga</h3><span>하다 yoga를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 14 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 14</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I sit on the floor to stay.</div>
                        <div class="kor-sentence">나는 앉다 floor를 (to stay)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I do exercise to stay.</div>
                        <div class="kor-sentence">나는 하다 exercise를 (to stay)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I sit on the floor-ing helps me stay.</div>
                        <div class="kor-sentence">(나는 앉다 floor를이) 나는 머물다 calm를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I do exercise-ing helps me stay.</div>
                        <div class="kor-sentence">(나는 하다 exercise를이) 나는 머물다 calm를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I sit on the floor about do.</div>
                        <div class="kor-sentence">나는 앉다 floor를 (do에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I stay calm after I sit on the floor.</div>
                        <div class="kor-sentence">나는 앉다 floor를 후에 나는 머물다 calm를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I do exercise because I sit on the floor.</div>
                        <div class="kor-sentence">나는 앉다 floor를 때문에 나는 하다 exercise를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I sit on the floor, so I stay calm.</div>
                        <div class="kor-sentence">나는 앉다 floor를, 그래서 나는 머물다 calm를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I stay calm, and I do exercise.</div>
                        <div class="kor-sentence">나는 머물다 calm를, 그리고 나는 하다 exercise를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I do exercise, and I sit on the floor.</div>
                        <div class="kor-sentence">나는 하다 exercise를, 그리고 나는 앉다 floor를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 15 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 15 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 15</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/I_act_calmly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I act calmly</h3><span>나는 행동하다 calmly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_bravely.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act bravely</h3><span>행동하다 bravely를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_calmly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act calmly</h3><span>행동하다 calmly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_carefully.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act carefully</h3><span>행동하다 주의깊게를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_kindly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act kindly</h3><span>행동하다 kindly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_naturally.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act naturally</h3><span>행동하다 naturally를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_quickly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act quickly</h3><span>행동하다 빠르게를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/43_act/act_slowly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>act slowly</h3><span>행동하다 천천히를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 15 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 15</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/I_walk_to_the_bathroom.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I walk to the bathroom</h3><span>나는 걷다 bathroom를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_bathroom.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the bathroom</h3><span>걷다 bathroom를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_bedroom.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the bedroom</h3><span>걷다 bedroom를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_dining_room.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the dining room</h3><span>걷다 dining room를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_door.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the door</h3><span>걷다 door를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_kitchen.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the kitchen</h3><span>걷다 kitchen를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_living_room.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the living room</h3><span>걷다 living room를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/44_walk_to/walk_to_the_window.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>walk to the window</h3><span>걷다 window를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 15 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 15</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/I_turn_on_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I turn on the water</h3><span>나는 켜다 water를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_TV.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the TV</h3><span>켜다 TV를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_computer.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the computer</h3><span>켜다 computer를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_light.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the light</h3><span>켜다 light를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_music.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the music</h3><span>켜다 music를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_radio.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the radio</h3><span>켜다 radio를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_tap.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the tap</h3><span>켜다 tap를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day15/45_turn_on/turn_on_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn on the water</h3><span>켜다 water를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 15 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 15</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I act calmly to walk to.</div>
                        <div class="kor-sentence">나는 행동하다 calmly를 (to walk to)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn on the water to walk to.</div>
                        <div class="kor-sentence">나는 켜다 water를 (to walk to)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I act calmly-ing helps me walk to.</div>
                        <div class="kor-sentence">(나는 행동하다 calmly를이) 나는 걷다 bathroom를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn on the water-ing helps me walk to.</div>
                        <div class="kor-sentence">(나는 켜다 water를이) 나는 걷다 bathroom를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I act calmly about turn on.</div>
                        <div class="kor-sentence">나는 행동하다 calmly를 (turn on에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I walk to the bathroom after I act calmly.</div>
                        <div class="kor-sentence">나는 행동하다 calmly를 후에 나는 걷다 bathroom를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn on the water because I act calmly.</div>
                        <div class="kor-sentence">나는 행동하다 calmly를 때문에 나는 켜다 water를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I act calmly, so I walk to the bathroom.</div>
                        <div class="kor-sentence">나는 행동하다 calmly를, 그래서 나는 걷다 bathroom를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I walk to the bathroom, and I turn on the water.</div>
                        <div class="kor-sentence">나는 걷다 bathroom를, 그리고 나는 켜다 water를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn on the water, and I act calmly.</div>
                        <div class="kor-sentence">나는 켜다 water를, 그리고 나는 행동하다 calmly를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 16 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 16 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 16</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/I_rub_my_hands.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I rub my hands</h3><span>나는 문지르다 hands를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_arms.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the arms</h3><span>문지르다 arms를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_cream.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the cream</h3><span>문지르다 cream를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_eyes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the eyes</h3><span>문지르다 eyes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_face.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the face</h3><span>문지르다 face를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_hands.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the hands</h3><span>문지르다 hands를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_skin.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the skin</h3><span>문지르다 skin를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/46_rub/rub_the_surface.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rub the surface</h3><span>문지르다 surface를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 16 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 16</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/I_wash_my_face.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I wash my face</h3><span>나는 씻다 face를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_arms.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the arms</h3><span>씻다 arms를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_body.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the body</h3><span>씻다 몸를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_face.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the face</h3><span>씻다 face를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_feet.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the feet</h3><span>씻다 feet를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the hair</h3><span>씻다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_hands.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the hands</h3><span>씻다 hands를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/47_wash/wash_the_legs.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wash the legs</h3><span>씻다 legs를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 16 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 16</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/I_have_a_shampoo.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I have a shampoo</h3><span>나는 가지다 shampoo를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_bath.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a bath</h3><span>가지다 bath를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_haircut.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a haircut</h3><span>가지다 haircut를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_perm.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a perm</h3><span>가지다 perm를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_shampoo.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a shampoo</h3><span>가지다 shampoo를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_shave.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a shave</h3><span>가지다 shave를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_shower.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a shower</h3><span>가지다 shower를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day16/48_have/have_a_tan.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a tan</h3><span>가지다 tan를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 16 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 16</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rub my hands to wash.</div>
                        <div class="kor-sentence">나는 문지르다 hands를 (to wash)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a shampoo to wash.</div>
                        <div class="kor-sentence">나는 가지다 shampoo를 (to wash)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rub my hands-ing helps me wash.</div>
                        <div class="kor-sentence">(나는 문지르다 hands를이) 나는 씻다 face를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a shampoo-ing helps me wash.</div>
                        <div class="kor-sentence">(나는 가지다 shampoo를이) 나는 씻다 face를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rub my hands about have.</div>
                        <div class="kor-sentence">나는 문지르다 hands를 (have에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I wash my face after I rub my hands.</div>
                        <div class="kor-sentence">나는 문지르다 hands를 후에 나는 씻다 face를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a shampoo because I rub my hands.</div>
                        <div class="kor-sentence">나는 문지르다 hands를 때문에 나는 가지다 shampoo를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rub my hands, so I wash my face.</div>
                        <div class="kor-sentence">나는 문지르다 hands를, 그래서 나는 씻다 face를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I wash my face, and I have a shampoo.</div>
                        <div class="kor-sentence">나는 씻다 face를, 그리고 나는 가지다 shampoo를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have a shampoo, and I rub my hands.</div>
                        <div class="kor-sentence">나는 가지다 shampoo를, 그리고 나는 문지르다 hands를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 17 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 17 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 17</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/I_grab_my_toothbrush.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I grab my toothbrush</h3><span>나는 잡다 toothbrush를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_bag.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the bag</h3><span>잡다 bag를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_ball.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the ball</h3><span>잡다 ball를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_book.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the book</h3><span>잡다 book를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_coat.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the coat</h3><span>잡다 coat를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_pen.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the pen</h3><span>잡다 pen를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_phone.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the phone</h3><span>잡다 phone를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/49_grab/grab_the_toothbrush.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>grab the toothbrush</h3><span>잡다 toothbrush를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 17 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 17</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/I_squeeze_the_toothpaste.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I squeeze the toothpaste</h3><span>나는 짜다 toothpaste를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_juice.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the juice</h3><span>짜다 juice를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_lemon.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the lemon</h3><span>짜다 lemon를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_lime.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the lime</h3><span>짜다 lime를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_orange.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the orange</h3><span>짜다 orange를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_sponge.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the sponge</h3><span>짜다 sponge를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_toothpaste.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the toothpaste</h3><span>짜다 toothpaste를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/50_squeeze/squeeze_the_tube.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>squeeze the tube</h3><span>짜다 tube를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 17 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 17</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/I_brush_my_teeth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I brush my teeth</h3><span>나는 닦다 teeth를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_cat.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the cat</h3><span>닦다 cat를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_clothes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the clothes</h3><span>닦다 clothes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_coat.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the coat</h3><span>닦다 coat를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_dog.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the dog</h3><span>닦다 dog를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the hair</h3><span>닦다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_shoes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the shoes</h3><span>닦다 shoes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day17/51_brush/brush_the_teeth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>brush the teeth</h3><span>닦다 teeth를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 17 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 17</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I grab my toothbrush to squeeze.</div>
                        <div class="kor-sentence">나는 잡다 toothbrush를 (to squeeze)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I brush my teeth to squeeze.</div>
                        <div class="kor-sentence">나는 닦다 teeth를 (to squeeze)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I grab my toothbrush-ing helps me squeeze.</div>
                        <div class="kor-sentence">(나는 잡다 toothbrush를이) 나는 짜다 toothpaste를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I brush my teeth-ing helps me squeeze.</div>
                        <div class="kor-sentence">(나는 닦다 teeth를이) 나는 짜다 toothpaste를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I grab my toothbrush about brush.</div>
                        <div class="kor-sentence">나는 잡다 toothbrush를 (brush에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I squeeze the toothpaste after I grab my toothbrush.</div>
                        <div class="kor-sentence">나는 잡다 toothbrush를 후에 나는 짜다 toothpaste를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I brush my teeth because I grab my toothbrush.</div>
                        <div class="kor-sentence">나는 잡다 toothbrush를 때문에 나는 닦다 teeth를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I grab my toothbrush, so I squeeze the toothpaste.</div>
                        <div class="kor-sentence">나는 잡다 toothbrush를, 그래서 나는 짜다 toothpaste를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I squeeze the toothpaste, and I brush my teeth.</div>
                        <div class="kor-sentence">나는 짜다 toothpaste를, 그리고 나는 닦다 teeth를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I brush my teeth, and I grab my toothbrush.</div>
                        <div class="kor-sentence">나는 닦다 teeth를, 그리고 나는 잡다 toothbrush를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 18 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 18 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 18</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/I_rinse_my_mouth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I rinse my mouth</h3><span>나는 헹구다 mouth를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_cup.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the cup</h3><span>헹구다 컵를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_dishes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the dishes</h3><span>헹구다 그릇를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_glass.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the glass</h3><span>헹구다 glass를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the hair</h3><span>헹구다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_mouth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the mouth</h3><span>헹구다 mouth를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_rice.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the rice</h3><span>헹구다 rice를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/52_rinse/rinse_the_vegetables.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>rinse the vegetables</h3><span>헹구다 vegetables를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 18 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 18</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/I_spit_out_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I spit out the water</h3><span>나는 뱉다 water를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_answer.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the answer</h3><span>뱉다 answer를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_coffee.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the coffee</h3><span>뱉다 coffee를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_food.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the food</h3><span>뱉다 food를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_gum.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the gum</h3><span>뱉다 gum를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_seed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the seed</h3><span>뱉다 seed를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the water</h3><span>뱉다 water를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/53_spit_out/spit_out_the_word.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>spit out the word</h3><span>뱉다 word를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 18 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 18</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/I_clean_the_sink.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I clean the sink</h3><span>나는 청소하다 싱크대를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_carpet.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the carpet</h3><span>청소하다 carpet를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_desk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the desk</h3><span>청소하다 desk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_floor.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the floor</h3><span>청소하다 floor를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_mirror.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the mirror</h3><span>청소하다 거울를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_sink.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the sink</h3><span>청소하다 싱크대를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_table.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the table</h3><span>청소하다 table를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day18/54_clean/clean_the_window.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>clean the window</h3><span>청소하다 window를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 18 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 18</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rinse my mouth to spit out.</div>
                        <div class="kor-sentence">나는 헹구다 mouth를 (to spit out)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I clean the sink to spit out.</div>
                        <div class="kor-sentence">나는 청소하다 싱크대를 (to spit out)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rinse my mouth-ing helps me spit out.</div>
                        <div class="kor-sentence">(나는 헹구다 mouth를이) 나는 뱉다 water를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I clean the sink-ing helps me spit out.</div>
                        <div class="kor-sentence">(나는 청소하다 싱크대를이) 나는 뱉다 water를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rinse my mouth about clean.</div>
                        <div class="kor-sentence">나는 헹구다 mouth를 (clean에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I spit out the water after I rinse my mouth.</div>
                        <div class="kor-sentence">나는 헹구다 mouth를 후에 나는 뱉다 water를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I clean the sink because I rinse my mouth.</div>
                        <div class="kor-sentence">나는 헹구다 mouth를 때문에 나는 청소하다 싱크대를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I rinse my mouth, so I spit out the water.</div>
                        <div class="kor-sentence">나는 헹구다 mouth를, 그래서 나는 뱉다 water를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I spit out the water, and I clean the sink.</div>
                        <div class="kor-sentence">나는 뱉다 water를, 그리고 나는 청소하다 싱크대를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I clean the sink, and I rinse my mouth.</div>
                        <div class="kor-sentence">나는 청소하다 싱크대를, 그리고 나는 헹구다 mouth를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 19 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 19 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 19</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/I_turn_off_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I turn off the water</h3><span>나는 끄다 water를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_TV.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the TV</h3><span>끄다 TV를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_computer.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the computer</h3><span>끄다 computer를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_light.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the light</h3><span>끄다 light를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_music.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the music</h3><span>끄다 music를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_radio.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the radio</h3><span>끄다 radio를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_tap.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the tap</h3><span>끄다 tap를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/55_turn_off/turn_off_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>turn off the water</h3><span>끄다 water를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 19 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 19</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/I_am_peaceful.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I am peaceful</h3><span>나는 되다 peaceful를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_cheerful.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be cheerful</h3><span>되다 cheerful를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_confident.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be confident</h3><span>되다 confident를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_generous.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be generous</h3><span>되다 generous를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_hopeful.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be hopeful</h3><span>되다 hopeful를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_old.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be old</h3><span>되다 늙은를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_peaceful.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be peaceful</h3><span>되다 peaceful를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/56_be/be_young.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be young</h3><span>되다 young를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 19 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 19</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/I_have_breakfast.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I have breakfast</h3><span>나는 가지다 breakfast를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_a_dessert.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a dessert</h3><span>가지다 dessert를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_a_meal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a meal</h3><span>가지다 meal를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_a_snack.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have a snack</h3><span>가지다 snack를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_breakfast.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have breakfast</h3><span>가지다 breakfast를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_dinner.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have dinner</h3><span>가지다 dinner를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_lunch.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have lunch</h3><span>가지다 lunch를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day19/57_have/have_supper.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>have supper</h3><span>가지다 supper를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 19 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 19</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the water to be.</div>
                        <div class="kor-sentence">나는 끄다 water를 (to be)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have breakfast to be.</div>
                        <div class="kor-sentence">나는 가지다 breakfast를 (to be)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the water-ing helps me be.</div>
                        <div class="kor-sentence">(나는 끄다 water를이) 나는 되다 peaceful를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have breakfast-ing helps me be.</div>
                        <div class="kor-sentence">(나는 가지다 breakfast를이) 나는 되다 peaceful를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the water about have.</div>
                        <div class="kor-sentence">나는 끄다 water를 (have에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am peaceful after I turn off the water.</div>
                        <div class="kor-sentence">나는 끄다 water를 후에 나는 되다 peaceful를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have breakfast because I turn off the water.</div>
                        <div class="kor-sentence">나는 끄다 water를 때문에 나는 가지다 breakfast를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I turn off the water, so I am peaceful.</div>
                        <div class="kor-sentence">나는 끄다 water를, 그래서 나는 되다 peaceful를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am peaceful, and I have breakfast.</div>
                        <div class="kor-sentence">나는 되다 peaceful를, 그리고 나는 가지다 breakfast를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I have breakfast, and I turn off the water.</div>
                        <div class="kor-sentence">나는 가지다 breakfast를, 그리고 나는 끄다 water를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 20 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 20 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 20</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/I_pour_the_milk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I pour the milk</h3><span>나는 붓다 milk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_coffee.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the coffee</h3><span>붓다 coffee를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_juice.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the juice</h3><span>붓다 juice를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_milk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the milk</h3><span>붓다 milk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_sauce.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the sauce</h3><span>붓다 sauce를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_soup.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the soup</h3><span>붓다 soup를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the water</h3><span>붓다 water를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/58_pour/pour_the_wine.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pour the wine</h3><span>붓다 wine를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 20 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 20</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/I_warm_the_milk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I warm the milk</h3><span>나는 데우다 milk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_house.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the house</h3><span>데우다 house를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_milk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the milk</h3><span>데우다 milk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_pan.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the pan</h3><span>데우다 pan를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_pot.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the pot</h3><span>데우다 pot를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_room.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the room</h3><span>데우다 room를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_soup.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the soup</h3><span>데우다 soup를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/59_warm/warm_the_water.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>warm the water</h3><span>데우다 water를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 20 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 20</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/I_eat_the_cereal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I eat the cereal</h3><span>나는 먹다 cereal를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_breakfast.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat breakfast</h3><span>먹다 breakfast를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_dinner.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat dinner</h3><span>먹다 dinner를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_lunch.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat lunch</h3><span>먹다 lunch를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_the_bread.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat the bread</h3><span>먹다 bread를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_the_cereal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat the cereal</h3><span>먹다 cereal를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_the_rice.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat the rice</h3><span>먹다 rice를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day20/60_eat/eat_the_soup.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>eat the soup</h3><span>먹다 soup를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 20 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 20</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pour the milk to warm.</div>
                        <div class="kor-sentence">나는 붓다 milk를 (to warm)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I eat the cereal to warm.</div>
                        <div class="kor-sentence">나는 먹다 cereal를 (to warm)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pour the milk-ing helps me warm.</div>
                        <div class="kor-sentence">(나는 붓다 milk를이) 나는 데우다 milk를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I eat the cereal-ing helps me warm.</div>
                        <div class="kor-sentence">(나는 먹다 cereal를이) 나는 데우다 milk를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pour the milk about eat.</div>
                        <div class="kor-sentence">나는 붓다 milk를 (eat에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I warm the milk after I pour the milk.</div>
                        <div class="kor-sentence">나는 붓다 milk를 후에 나는 데우다 milk를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I eat the cereal because I pour the milk.</div>
                        <div class="kor-sentence">나는 붓다 milk를 때문에 나는 먹다 cereal를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pour the milk, so I warm the milk.</div>
                        <div class="kor-sentence">나는 붓다 milk를, 그래서 나는 데우다 milk를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I warm the milk, and I eat the cereal.</div>
                        <div class="kor-sentence">나는 데우다 milk를, 그리고 나는 먹다 cereal를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I eat the cereal, and I pour the milk.</div>
                        <div class="kor-sentence">나는 먹다 cereal를, 그리고 나는 붓다 milk를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 21 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 21 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 21</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/I_check_my_phone.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I check my phone</h3><span>나는 확인하다 phone를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_email.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the email</h3><span>확인하다 이메일를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_items.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the items</h3><span>확인하다 items를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_map.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the map</h3><span>확인하다 map를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_phone.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the phone</h3><span>확인하다 phone를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_result.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the result</h3><span>확인하다 result를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_time.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the time</h3><span>확인하다 시간를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/61_check/check_the_weather.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>check the weather</h3><span>확인하다 weather를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 21 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 21</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/I_browse_social_media.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I browse social media</h3><span>나는 둘러보다 social media를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_social_media.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse social media</h3><span>둘러보다 social media를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_the_book.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse the book</h3><span>둘러보다 book를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_the_gallery.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse the gallery</h3><span>둘러보다 gallery를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_the_internet.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse the internet</h3><span>둘러보다 internet를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_the_site.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse the site</h3><span>둘러보다 site를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_the_store.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse the store</h3><span>둘러보다 store를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/62_browse/browse_the_web.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>browse the web</h3><span>둘러보다 web를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 21 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 21</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/I_send_the_message.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I send the message</h3><span>나는 보내다 메시지를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_email.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the email</h3><span>보내다 이메일를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_fax.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the fax</h3><span>보내다 fax를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_file.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the file</h3><span>보내다 file를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_invitation.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the invitation</h3><span>보내다 invitation를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_message.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the message</h3><span>보내다 메시지를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_photo.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the photo</h3><span>보내다 photo를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day21/63_send/send_the_report.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>send the report</h3><span>보내다 보고서를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 21 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 21</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I check my phone to browse.</div>
                        <div class="kor-sentence">나는 확인하다 phone를 (to browse)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I send the message to browse.</div>
                        <div class="kor-sentence">나는 보내다 메시지를 (to browse)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I check my phone-ing helps me browse.</div>
                        <div class="kor-sentence">(나는 확인하다 phone를이) 나는 둘러보다 social media를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I send the message-ing helps me browse.</div>
                        <div class="kor-sentence">(나는 보내다 메시지를이) 나는 둘러보다 social media를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I check my phone about send.</div>
                        <div class="kor-sentence">나는 확인하다 phone를 (send에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I browse social media after I check my phone.</div>
                        <div class="kor-sentence">나는 확인하다 phone를 후에 나는 둘러보다 social media를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I send the message because I check my phone.</div>
                        <div class="kor-sentence">나는 확인하다 phone를 때문에 나는 보내다 메시지를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I check my phone, so I browse social media.</div>
                        <div class="kor-sentence">나는 확인하다 phone를, 그래서 나는 둘러보다 social media를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I browse social media, and I send the message.</div>
                        <div class="kor-sentence">나는 둘러보다 social media를, 그리고 나는 보내다 메시지를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I send the message, and I check my phone.</div>
                        <div class="kor-sentence">나는 보내다 메시지를, 그리고 나는 확인하다 phone를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 22 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 22 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 22</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/I_face_the_mirror.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I face the mirror</h3><span>나는 마주하다 거울를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_audience.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the audience</h3><span>마주하다 청중를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_camera.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the camera</h3><span>마주하다 camera를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_crowd.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the crowd</h3><span>마주하다 crowd를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_front.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the front</h3><span>마주하다 front를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_mirror.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the mirror</h3><span>마주하다 거울를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_wall.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the wall</h3><span>마주하다 wall를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/64_face/face_the_window.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>face the window</h3><span>마주하다 window를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 22 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 22</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/I_choose_the_green_shirt.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I choose the green shirt</h3><span>나는 선택하다 green shirt를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_clothes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the clothes</h3><span>선택하다 clothes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_color.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the color</h3><span>선택하다 color를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_design.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the design</h3><span>선택하다 design를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_food.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the food</h3><span>선택하다 food를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_fruit.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the fruit</h3><span>선택하다 fruit를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_menu.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the menu</h3><span>선택하다 menu를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/65_choose/choose_the_shirt.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>choose the shirt</h3><span>선택하다 shirt를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 22 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 22</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/I_smile_brightly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I smile brightly</h3><span>나는 웃다 brightly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_brightly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile brightly</h3><span>웃다 brightly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_gently.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile gently</h3><span>웃다 gently를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_happily.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile happily</h3><span>웃다 happily를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_kindly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile kindly</h3><span>웃다 kindly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_proudly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile proudly</h3><span>웃다 proudly를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_quietly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile quietly</h3><span>웃다 조용히를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day22/66_smile/smile_sweetly.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>smile sweetly</h3><span>웃다 sweetly를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 22 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 22</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the mirror to choose.</div>
                        <div class="kor-sentence">나는 마주하다 거울를 (to choose)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I smile brightly to choose.</div>
                        <div class="kor-sentence">나는 웃다 brightly를 (to choose)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the mirror-ing helps me choose.</div>
                        <div class="kor-sentence">(나는 마주하다 거울를이) 나는 선택하다 green shirt를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I smile brightly-ing helps me choose.</div>
                        <div class="kor-sentence">(나는 웃다 brightly를이) 나는 선택하다 green shirt를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the mirror about smile.</div>
                        <div class="kor-sentence">나는 마주하다 거울를 (smile에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I choose the green shirt after I face the mirror.</div>
                        <div class="kor-sentence">나는 마주하다 거울를 후에 나는 선택하다 green shirt를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I smile brightly because I face the mirror.</div>
                        <div class="kor-sentence">나는 마주하다 거울를 때문에 나는 웃다 brightly를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I face the mirror, so I choose the green shirt.</div>
                        <div class="kor-sentence">나는 마주하다 거울를, 그래서 나는 선택하다 green shirt를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I choose the green shirt, and I smile brightly.</div>
                        <div class="kor-sentence">나는 선택하다 green shirt를, 그리고 나는 웃다 brightly를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I smile brightly, and I face the mirror.</div>
                        <div class="kor-sentence">나는 웃다 brightly를, 그리고 나는 마주하다 거울를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 23 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 23 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 23</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/I_button_up_my_shirt.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I button up my shirt</h3><span>나는 단추를 채우다 shirt를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_blouse.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the blouse</h3><span>단추를 채우다 blouse를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_coat.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the coat</h3><span>단추를 채우다 coat를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_jacket.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the jacket</h3><span>단추를 채우다 jacket를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_pants.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the pants</h3><span>단추를 채우다 pants를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_shirt.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the shirt</h3><span>단추를 채우다 shirt를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_suit.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the suit</h3><span>단추를 채우다 suit를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/67_button_up/button_up_the_trousers.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>button up the trousers</h3><span>단추를 채우다 trousers를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 23 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 23</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/I_wear_my_pants.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I wear my pants</h3><span>나는 입다 pants를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_clothes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the clothes</h3><span>입다 clothes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_coat.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the coat</h3><span>입다 coat를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_dress.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the dress</h3><span>입다 dress를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_pants.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the pants</h3><span>입다 pants를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_shirt.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the shirt</h3><span>입다 shirt를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_suit.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the suit</h3><span>입다 suit를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/68_wear/wear_the_uniform.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>wear the uniform</h3><span>입다 uniform를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 23 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 23</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/I_get_dressed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I get dressed</h3><span>나는 얻다 dressed를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_closed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get closed</h3><span>얻다 closed를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_dressed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get dressed</h3><span>얻다 dressed를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_finished.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get finished</h3><span>얻다 finished를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_married.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get married</h3><span>얻다 married를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_opened.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get opened</h3><span>얻다 opened를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_started.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get started</h3><span>얻다 started를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day23/69_get/get_undressed.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>get undressed</h3><span>얻다 undressed를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 23 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 23</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I button up my shirt to wear.</div>
                        <div class="kor-sentence">나는 단추를 채우다 shirt를 (to wear)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I get dressed to wear.</div>
                        <div class="kor-sentence">나는 얻다 dressed를 (to wear)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I button up my shirt-ing helps me wear.</div>
                        <div class="kor-sentence">(나는 단추를 채우다 shirt를이) 나는 입다 pants를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I get dressed-ing helps me wear.</div>
                        <div class="kor-sentence">(나는 얻다 dressed를이) 나는 입다 pants를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I button up my shirt about get.</div>
                        <div class="kor-sentence">나는 단추를 채우다 shirt를 (get에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I wear my pants after I button up my shirt.</div>
                        <div class="kor-sentence">나는 단추를 채우다 shirt를 후에 나는 입다 pants를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I get dressed because I button up my shirt.</div>
                        <div class="kor-sentence">나는 단추를 채우다 shirt를 때문에 나는 얻다 dressed를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I button up my shirt, so I wear my pants.</div>
                        <div class="kor-sentence">나는 단추를 채우다 shirt를, 그래서 나는 입다 pants를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I wear my pants, and I get dressed.</div>
                        <div class="kor-sentence">나는 입다 pants를, 그리고 나는 얻다 dressed를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I get dressed, and I button up my shirt.</div>
                        <div class="kor-sentence">나는 얻다 dressed를, 그리고 나는 단추를 채우다 shirt를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 24 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 24 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 24</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/I_care_for_my_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I care for my hair</h3><span>나는 돌보다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_animal.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the animal</h3><span>돌보다 animal를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_child.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the child</h3><span>돌보다 child를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_face.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the face</h3><span>돌보다 face를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the hair</h3><span>돌보다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_health.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the health</h3><span>돌보다 health를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_skin.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the skin</h3><span>돌보다 skin를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/70_care_for/care_for_the_teeth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>care for the teeth</h3><span>돌보다 teeth를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 24 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 24</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/I_tie_my_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I tie my hair</h3><span>나는 묶다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_bag.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the bag</h3><span>묶다 bag를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_ends.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the ends</h3><span>묶다 ends를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_hair.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the hair</h3><span>묶다 머리를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_package.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the package</h3><span>묶다 package를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_ribbon.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the ribbon</h3><span>묶다 ribbon를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_shoelaces.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the shoelaces</h3><span>묶다 shoelaces를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/71_tie/tie_the_string.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>tie the string</h3><span>묶다 string를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 24 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 24</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/I_pack_the_bag.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I pack the bag</h3><span>나는 싸다 bag를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_bag.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the bag</h3><span>싸다 bag를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_box.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the box</h3><span>싸다 box를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_clothes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the clothes</h3><span>싸다 clothes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_food.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the food</h3><span>싸다 food를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_lunch.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the lunch</h3><span>싸다 lunch를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_suitcase.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the suitcase</h3><span>싸다 suitcase를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day24/72_pack/pack_the_trunk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pack the trunk</h3><span>싸다 trunk를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 24 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 24</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I care for my hair to tie.</div>
                        <div class="kor-sentence">나는 돌보다 머리를 (to tie)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pack the bag to tie.</div>
                        <div class="kor-sentence">나는 싸다 bag를 (to tie)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I care for my hair-ing helps me tie.</div>
                        <div class="kor-sentence">(나는 돌보다 머리를이) 나는 묶다 머리를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pack the bag-ing helps me tie.</div>
                        <div class="kor-sentence">(나는 싸다 bag를이) 나는 묶다 머리를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I care for my hair about pack.</div>
                        <div class="kor-sentence">나는 돌보다 머리를 (pack에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I tie my hair after I care for my hair.</div>
                        <div class="kor-sentence">나는 돌보다 머리를 후에 나는 묶다 머리를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pack the bag because I care for my hair.</div>
                        <div class="kor-sentence">나는 돌보다 머리를 때문에 나는 싸다 bag를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I care for my hair, so I tie my hair.</div>
                        <div class="kor-sentence">나는 돌보다 머리를, 그래서 나는 묶다 머리를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I tie my hair, and I pack the bag.</div>
                        <div class="kor-sentence">나는 묶다 머리를, 그리고 나는 싸다 bag를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pack the bag, and I care for my hair.</div>
                        <div class="kor-sentence">나는 싸다 bag를, 그리고 나는 돌보다 머리를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 25 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 25 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 25</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/I_protect_my_eyes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I protect my eyes</h3><span>나는 보호하다 eyes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_animals.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the animals</h3><span>보호하다 animals를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_body.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the body</h3><span>보호하다 몸를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_earth.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the earth</h3><span>보호하다 earth를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_eyes.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the eyes</h3><span>보호하다 eyes를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_head.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the head</h3><span>보호하다 head를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_plants.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the plants</h3><span>보호하다 plants를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/73_protect/protect_the_skin.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>protect the skin</h3><span>보호하다 skin를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 25 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 25</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/I_block_the_sun.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I block the sun</h3><span>나는 막다 sun를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_entrance.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the entrance</h3><span>막다 entrance를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_exit.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the exit</h3><span>막다 exit를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_light.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the light</h3><span>막다 light를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_sight.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the sight</h3><span>막다 sight를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_sun.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the sun</h3><span>막다 sun를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_traffic.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the traffic</h3><span>막다 traffic를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/74_block/block_the_way.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>block the way</h3><span>막다 way를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 25 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 25</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/I_leave_the_house.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I leave the house</h3><span>나는 떠나다 house를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_city.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the city</h3><span>떠나다 city를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_country.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the country</h3><span>떠나다 country를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_house.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the house</h3><span>떠나다 house를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_room.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the room</h3><span>떠나다 room를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_school.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the school</h3><span>떠나다 school를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_table.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the table</h3><span>떠나다 table를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day25/75_leave/leave_the_team.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>leave the team</h3><span>떠나다 team를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 25 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 25</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I protect my eyes to block.</div>
                        <div class="kor-sentence">나는 보호하다 eyes를 (to block)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I leave the house to block.</div>
                        <div class="kor-sentence">나는 떠나다 house를 (to block)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I protect my eyes-ing helps me block.</div>
                        <div class="kor-sentence">(나는 보호하다 eyes를이) 나는 막다 sun를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I leave the house-ing helps me block.</div>
                        <div class="kor-sentence">(나는 떠나다 house를이) 나는 막다 sun를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I protect my eyes about leave.</div>
                        <div class="kor-sentence">나는 보호하다 eyes를 (leave에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I block the sun after I protect my eyes.</div>
                        <div class="kor-sentence">나는 보호하다 eyes를 후에 나는 막다 sun를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I leave the house because I protect my eyes.</div>
                        <div class="kor-sentence">나는 보호하다 eyes를 때문에 나는 떠나다 house를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I protect my eyes, so I block the sun.</div>
                        <div class="kor-sentence">나는 보호하다 eyes를, 그래서 나는 막다 sun를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I block the sun, and I leave the house.</div>
                        <div class="kor-sentence">나는 막다 sun를, 그리고 나는 떠나다 house를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I leave the house, and I protect my eyes.</div>
                        <div class="kor-sentence">나는 떠나다 house를, 그리고 나는 보호하다 eyes를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 26 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 26 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 26</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/I_look_at_the_sky.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I look at the sky</h3><span>나는 보다 sky를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_camera.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the camera</h3><span>보다 camera를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_board.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the board</h3><span>보다 board를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_flower.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the flower</h3><span>보다 flower를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_front.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the front</h3><span>보다 front를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_menu.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the menu</h3><span>보다 menu를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_sky.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the sky</h3><span>보다 sky를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/76_look_at/look_at_the_street.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>look at the street</h3><span>보다 street를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 26 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 26</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/I_am_happy.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I am happy</h3><span>나는 되다 happy를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_bad.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be bad</h3><span>되다 bad를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_fine.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be fine</h3><span>되다 fine를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_glad.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be glad</h3><span>되다 glad를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_good.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be good</h3><span>되다 good를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_happy.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be happy</h3><span>되다 happy를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_sad.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be sad</h3><span>되다 슬픈를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/77_be/be_unhappy.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>be unhappy</h3><span>되다 unhappy를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 26 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 26</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/I_like_the_sky.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I like the sky</h3><span>나는 좋아하다 sky를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_dance.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the dance</h3><span>좋아하다 dance를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_movie.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the movie</h3><span>좋아하다 movie를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_picture.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the picture</h3><span>좋아하다 picture를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_rain.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the rain</h3><span>좋아하다 rain를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_sky.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the sky</h3><span>좋아하다 sky를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_sun.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the sun</h3><span>좋아하다 sun를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day26/78_like/like_the_wind.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>like the wind</h3><span>좋아하다 wind를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 26 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 26</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look at the sky to be.</div>
                        <div class="kor-sentence">나는 보다 sky를 (to be)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I like the sky to be.</div>
                        <div class="kor-sentence">나는 좋아하다 sky를 (to be)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look at the sky-ing helps me be.</div>
                        <div class="kor-sentence">(나는 보다 sky를이) 나는 되다 happy를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I like the sky-ing helps me be.</div>
                        <div class="kor-sentence">(나는 좋아하다 sky를이) 나는 되다 happy를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look at the sky about like.</div>
                        <div class="kor-sentence">나는 보다 sky를 (like에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am happy after I look at the sky.</div>
                        <div class="kor-sentence">나는 보다 sky를 후에 나는 되다 happy를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I like the sky because I look at the sky.</div>
                        <div class="kor-sentence">나는 보다 sky를 때문에 나는 좋아하다 sky를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I look at the sky, so I am happy.</div>
                        <div class="kor-sentence">나는 보다 sky를, 그래서 나는 되다 happy를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I am happy, and I like the sky.</div>
                        <div class="kor-sentence">나는 되다 happy를, 그리고 나는 좋아하다 sky를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I like the sky, and I look at the sky.</div>
                        <div class="kor-sentence">나는 좋아하다 sky를, 그리고 나는 보다 sky를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 27 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 27 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 27</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/I_pass_the_park.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I pass the park</h3><span>나는 통과하다 park를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_bank.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the bank</h3><span>통과하다 bank를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_hospital.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the hospital</h3><span>통과하다 hospital를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_hotel.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the hotel</h3><span>통과하다 hotel를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_museum.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the museum</h3><span>통과하다 museum를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_park.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the park</h3><span>통과하다 park를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_port.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the port</h3><span>통과하다 port를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/79_pass/pass_the_shop.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>pass the shop</h3><span>통과하다 shop를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 27 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 27</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/I_climb_the_hill.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I climb the hill</h3><span>나는 오르다 hill를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_hill.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the hill</h3><span>오르다 hill를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_ladder.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the ladder</h3><span>오르다 ladder를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_rock.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the rock</h3><span>오르다 rock를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_rope.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the rope</h3><span>오르다 rope를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_stairs.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the stairs</h3><span>오르다 stairs를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_steps.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the steps</h3><span>오르다 steps를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/80_climb/climb_the_tree.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>climb the tree</h3><span>오르다 나무를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 27 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 27</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/I_take_a_deep_breath.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I take a deep breath</h3><span>나는 가져가다 deep breath를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_break.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a break</h3><span>가져가다 break를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_breath.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a breath</h3><span>가져가다 breath를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_nap.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a nap</h3><span>가져가다 nap를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_pause.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a pause</h3><span>가져가다 pause를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_recess.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a recess</h3><span>가져가다 recess를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_rest.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a rest</h3><span>가져가다 rest를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day27/81_take/take_a_sleep.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>take a sleep</h3><span>가져가다 sleep를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 27 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 27</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass the park to climb.</div>
                        <div class="kor-sentence">나는 통과하다 park를 (to climb)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I take a deep breath to climb.</div>
                        <div class="kor-sentence">나는 가져가다 deep breath를 (to climb)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass the park-ing helps me climb.</div>
                        <div class="kor-sentence">(나는 통과하다 park를이) 나는 오르다 hill를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I take a deep breath-ing helps me climb.</div>
                        <div class="kor-sentence">(나는 가져가다 deep breath를이) 나는 오르다 hill를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass the park about take.</div>
                        <div class="kor-sentence">나는 통과하다 park를 (take에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I climb the hill after I pass the park.</div>
                        <div class="kor-sentence">나는 통과하다 park를 후에 나는 오르다 hill를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I take a deep breath because I pass the park.</div>
                        <div class="kor-sentence">나는 통과하다 park를 때문에 나는 가져가다 deep breath를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I pass the park, so I climb the hill.</div>
                        <div class="kor-sentence">나는 통과하다 park를, 그래서 나는 오르다 hill를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I climb the hill, and I take a deep breath.</div>
                        <div class="kor-sentence">나는 오르다 hill를, 그리고 나는 가져가다 deep breath를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I take a deep breath, and I pass the park.</div>
                        <div class="kor-sentence">나는 가져가다 deep breath를, 그리고 나는 통과하다 park를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 28 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 28 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 28</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/I_obey_the_rule.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I obey the rule</h3><span>나는 따르다 rule를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_command.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the command</h3><span>따르다 command를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_decision.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the decision</h3><span>따르다 decision를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_instruction.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the instruction</h3><span>따르다 instruction를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_law.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the law</h3><span>따르다 law를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_order.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the order</h3><span>따르다 order를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_rule.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the rule</h3><span>따르다 rule를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/82_obey/obey_the_sign.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>obey the sign</h3><span>따르다 sign를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 28 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 28</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/I_cross_the_street.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I cross the street</h3><span>나는 건너다 street를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_border.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the border</h3><span>건너다 border를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_bridge.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the bridge</h3><span>건너다 bridge를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_crosswalk.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the crosswalk</h3><span>건너다 crosswalk를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_line.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the line</h3><span>건너다 line를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_river.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the river</h3><span>건너다 river를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_road.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the road</h3><span>건너다 road를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/83_cross/cross_the_street.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>cross the street</h3><span>건너다 street를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 28 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 28</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/I_use_the_stairs.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I use the stairs</h3><span>나는 사용하다 stairs를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_bus.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the bus</h3><span>사용하다 bus를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_car.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the car</h3><span>사용하다 car를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_elevator.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the elevator</h3><span>사용하다 elevator를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_escalator.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the escalator</h3><span>사용하다 escalator를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_stairs.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the stairs</h3><span>사용하다 stairs를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_subway.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the subway</h3><span>사용하다 subway를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day28/84_use/use_the_train.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>use the train</h3><span>사용하다 train를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 28 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 28</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I obey the rule to cross.</div>
                        <div class="kor-sentence">나는 따르다 rule를 (to cross)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I use the stairs to cross.</div>
                        <div class="kor-sentence">나는 사용하다 stairs를 (to cross)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I obey the rule-ing helps me cross.</div>
                        <div class="kor-sentence">(나는 따르다 rule를이) 나는 건너다 street를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I use the stairs-ing helps me cross.</div>
                        <div class="kor-sentence">(나는 사용하다 stairs를이) 나는 건너다 street를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I obey the rule about use.</div>
                        <div class="kor-sentence">나는 따르다 rule를 (use에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I cross the street after I obey the rule.</div>
                        <div class="kor-sentence">나는 따르다 rule를 후에 나는 건너다 street를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I use the stairs because I obey the rule.</div>
                        <div class="kor-sentence">나는 따르다 rule를 때문에 나는 사용하다 stairs를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I obey the rule, so I cross the street.</div>
                        <div class="kor-sentence">나는 따르다 rule를, 그래서 나는 건너다 street를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I cross the street, and I use the stairs.</div>
                        <div class="kor-sentence">나는 건너다 street를, 그리고 나는 사용하다 stairs를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I use the stairs, and I obey the rule.</div>
                        <div class="kor-sentence">나는 사용하다 stairs를, 그리고 나는 따르다 rule를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 29 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 29 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 29</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/I_see_the_park.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I see the park</h3><span>나는 보다 park를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_cat.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the cat</h3><span>보다 cat를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_dog.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the dog</h3><span>보다 dog를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_garden.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the garden</h3><span>보다 garden를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_light.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the light</h3><span>보다 light를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_moon.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the moon</h3><span>보다 moon를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_park.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the park</h3><span>보다 park를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/85_see/see_the_sun.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>see the sun</h3><span>보다 sun를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 29 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 29</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/I_listen_to_the_wind.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I listen to the wind</h3><span>나는 듣다 wind를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_alarm.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the alarm</h3><span>듣다 alarm를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_message.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the message</h3><span>듣다 메시지를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_noise.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the noise</h3><span>듣다 noise를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_rain.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the rain</h3><span>듣다 rain를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_sound.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the sound</h3><span>듣다 sound를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_speech.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the speech</h3><span>듣다 speech를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/86_listen_to/listen_to_the_wind.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>listen to the wind</h3><span>듣다 wind를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 29 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 29</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/I_imagine_my_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I imagine my future</h3><span>나는 상상하다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_dream.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the dream</h3><span>상상하다 꿈를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_future.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the future</h3><span>상상하다 미래를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_life.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the life</h3><span>상상하다 생활를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_picture.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the picture</h3><span>상상하다 picture를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_possibility.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the possibility</h3><span>상상하다 possibility를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_surprise.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the surprise</h3><span>상상하다 surprise를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day29/87_imagine/imagine_the_world.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>imagine the world</h3><span>상상하다 world를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 29 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 29</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I see the park to listen to.</div>
                        <div class="kor-sentence">나는 보다 park를 (to listen to)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I imagine my future to listen to.</div>
                        <div class="kor-sentence">나는 상상하다 미래를 (to listen to)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I see the park-ing helps me listen to.</div>
                        <div class="kor-sentence">(나는 보다 park를이) 나는 듣다 wind를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I imagine my future-ing helps me listen to.</div>
                        <div class="kor-sentence">(나는 상상하다 미래를이) 나는 듣다 wind를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I see the park about imagine.</div>
                        <div class="kor-sentence">나는 보다 park를 (imagine에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I listen to the wind after I see the park.</div>
                        <div class="kor-sentence">나는 보다 park를 후에 나는 듣다 wind를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I imagine my future because I see the park.</div>
                        <div class="kor-sentence">나는 보다 park를 때문에 나는 상상하다 미래를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I see the park, so I listen to the wind.</div>
                        <div class="kor-sentence">나는 보다 park를, 그래서 나는 듣다 wind를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I listen to the wind, and I imagine my future.</div>
                        <div class="kor-sentence">나는 듣다 wind를, 그리고 나는 상상하다 미래를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I imagine my future, and I see the park.</div>
                        <div class="kor-sentence">나는 상상하다 미래를, 그리고 나는 보다 park를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     Day 30 세트 (페이지 1 ~ 4)
=========================================== -->
<!-- Day 30 - 본문 페이지 1 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 30</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/I_arrive_at_school.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I arrive at school</h3><span>나는 도착하다 school를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_home.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at home</h3><span>도착하다 home를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_school.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at school</h3><span>도착하다 school를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_the_gate.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at the gate</h3><span>도착하다 gate를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_the_hotel.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at the hotel</h3><span>도착하다 hotel를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_the_office.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at the office</h3><span>도착하다 office를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_the_station.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at the station</h3><span>도착하다 station를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/88_arrive_at/arrive_at_work.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>arrive at work</h3><span>도착하다 work를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 01</span></footer>
        </div>
    </div>
</div>

<!-- Day 30 - 본문 페이지 2 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 30</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/I_come_to_class.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I come to class</h3><span>나는 오다 수업를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_church.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to church</h3><span>오다 church를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_class.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to class</h3><span>오다 수업를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_school.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to school</h3><span>오다 school를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_the_concert.png.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to the concert</h3><span>오다 콘서트를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_the_meeting.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to the meeting</h3><span>오다 meeting를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_the_party.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to the party</h3><span>오다 party를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/89_come_to/come_to_the_stage.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>come to the stage</h3><span>오다 stage를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 02</span></footer>
        </div>
    </div>
</div>

<!-- Day 30 - 본문 페이지 3 -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 30</div></div>
            <div class="header-center">
                <h1><span class="font-red drop-shadow">청킹</span>으로 쉽게 영어말하기</h1>
                <p class="sub-header-text">(<span class="font-red">Chunking</span>-Based Easy Speaking)</p>
            </div>
            <div class="header-right"></div>
        </header>

        <section class="chunk-grid">
            <div class="chunk-card main-point"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/I_bow_to_the_teacher.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+1'"></div><div class="note-area dark"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>I bow to the teacher</h3><span>나는 인사하다 teacher를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_audience.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+2'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the audience</h3><span>인사하다 청중를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_fan.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+3'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the fan</h3><span>인사하다 fan를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_friend.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+4'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the friend</h3><span>인사하다 friend를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_guest.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+5'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the guest</h3><span>인사하다 guest를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_master.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+6'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the master</h3><span>인사하다 master를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_parent.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+7'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the parent</h3><span>인사하다 parent를</span></div></div></div>
            <div class="chunk-card"><div class="img-container"><img loading="lazy" src="./img/final/day30/90_bow_to/bow_to_the_teacher.png" onerror="this.src='https://placehold.co/400x400/FF8FA3/FFF?text=Img+8'"></div><div class="note-area light"><div class="note-line"></div><div class="note-margin"></div><div class="note-text-wrap"><h3>bow to the teacher</h3><span>인사하다 teacher를</span></div></div></div>
            <div class="chunk-card mode-switch-card">
                <div class="app-mode-btn active"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                <div class="app-mode-btn"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 03</span></footer>
        </div>
    </div>
</div>

<!-- Day 30 - 본문 페이지 4 (매직 카드 리스트) -->
<div class="sheet page-break">
    <div class="bg-deco"></div>
    <div class="z-content">
        <header class="main-header">
            <div class="header-left"><div class="day-badge">Day 30</div></div>
            <div class="header-center"></div>
            <div class="header-right">
                <div class="mode-wrapper no-print-temp">
                    <div class="app-mode-btn"><img src="./img/wct01_n.png" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'"><span>청킹기본</span></div>
                    <div class="app-mode-btn active"><img src="./img/wct02.png" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'"><span>청킹변화</span></div>
                </div>
            </div>
        </header>

        <section class="magic-card-list">
            <div class="magic-card">
                <div class="magic-number-tag">1</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I arrive at school to come to.</div>
                        <div class="kor-sentence">나는 도착하다 school를 (to come to)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">2</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">to(부정사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I bow to the teacher to come to.</div>
                        <div class="kor-sentence">나는 인사하다 teacher를 (to come to)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">3</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I arrive at school-ing helps me come to.</div>
                        <div class="kor-sentence">(나는 도착하다 school를이) 나는 오다 수업를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">4</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">ing(동명사)</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I bow to the teacher-ing helps me come to.</div>
                        <div class="kor-sentence">(나는 인사하다 teacher를이) 나는 오다 수업를에 도움</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">5</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">전치사</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I arrive at school about bow to.</div>
                        <div class="kor-sentence">나는 도착하다 school를 (bow to에 대해)</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">6</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I come to class after I arrive at school.</div>
                        <div class="kor-sentence">나는 도착하다 school를 후에 나는 오다 수업를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">7</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">부사절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I bow to the teacher because I arrive at school.</div>
                        <div class="kor-sentence">나는 도착하다 school를 때문에 나는 인사하다 teacher를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">8</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I arrive at school, so I come to class.</div>
                        <div class="kor-sentence">나는 도착하다 school를, 그래서 나는 오다 수업를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">9</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I come to class, and I bow to the teacher.</div>
                        <div class="kor-sentence">나는 오다 수업를, 그리고 나는 인사하다 teacher를</div>
                    </div>
                </div>
            </div>

            <div class="magic-card">
                <div class="magic-number-tag">10</div>
                <div class="magic-content">
                    <div class="grammar-visual-box">
                        <img src="./img/wct01_n.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:magicwand.svg'">
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <span class="magic-connector-tag">등위절</span>
                        <span class="text-plus"><i class="fa-solid fa-plus"></i></span>
                        <img src="./img/wct02.png" class="wizard-icon" onerror="this.src='https://api.iconify.design/fxemoji:sparkles.svg'">
                    </div>
                    <div class="magic-text-box">
                        <div class="eng-sentence">I bow to the teacher, and I arrive at school.</div>
                        <div class="kor-sentence">나는 인사하다 teacher를, 그리고 나는 도착하다 school를</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer-wrapper">
            <div class="copyright-box">
                <p class="copyright-text">
                    <strong>ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                    상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다. 개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
                </p>
            </div>
            <footer class="page-footer"><span>© <span class="font-red">Chunking</span> English Kids&Mom</span><span>Page 04</span></footer>
        </div>
    </div>
</div>


<!-- ==========================================
     뒷표지
=========================================== -->
<div class="sheet cover-sheet page-break" style="background: #FAFAFA;">
    <div style="position: absolute; top: 15px; bottom: 15px; left: 15px; right: 15px; border: 1px solid #E2E8F0; border-radius: 12px; pointer-events: none;"></div>
    <div style="position: absolute; top: 20px; bottom: 20px; left: 20px; right: 20px; border: 1px solid rgba(255,126,150,0.3); border-radius: 8px; pointer-events: none;"></div>

    <div class="z-content" style="text-align: center; display: flex; flex-direction: column; justify-content: center; padding: 20px 30px; position: relative; height: 100%;">

        <h2 style="font-family: 'Pretendard', sans-serif; font-size: 2rem; font-weight: 800; color: var(--red-point); margin-bottom: 25px; line-height: 1.4; letter-spacing: -1px;">
            내 아이와 나의 인생을 바꿀 기회...<br>놓치지 마세요!
        </h2>

        <div style="margin-bottom: 25px;">
            <p style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px; letter-spacing: -0.5px;">영어로.... 누구나... 즉시... 저절로 말한다!</p>
            <p style="font-size: 1.15rem; font-weight: 500; color: var(--text-sub); letter-spacing: -0.5px;">보고 듣고 생각한 것을</p>
        </div>

        <div style="margin-bottom: 25px; display: flex; justify-content: center; align-items: center; gap: 12px;">
            <i class="fa-solid fa-quote-left" style="color: #FFB3C1; font-size: 1.5rem; opacity: 0.7;"></i>
            <span style="font-size: 1.25rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px;">
                영어 말하기는 공부가 아닙니다. 단지 트레이닝 훈련입니다!
            </span>
            <i class="fa-solid fa-quote-right" style="color: #FFB3C1; font-size: 1.5rem; opacity: 0.7;"></i>
        </div>

        <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-main); margin-bottom: 15px; font-weight: 500; letter-spacing: -0.5px;">
            영어단어 영어문법 영어어순을 각각 따로따로 학습하여<br>
            영어로 말할 때 무엇을 어떻게 할 것인가 고민하던 것은<br>
            <strong style="color: var(--red-point); font-size: 1.35rem; font-weight: 900; display: inline-block; margin-top: 8px;">이제 No No No!</strong>
        </p>

        <img src="./img/exc_n1.png" onerror="this.src='https://api.iconify.design/fxemoji:rocket.svg'" style="width: 160px; height: 160px; margin: 5px auto 20px; filter: drop-shadow(0 15px 25px rgba(255,126,150,0.25)); object-fit: contain;">

        <p style="font-size: 1.1rem; line-height: 1.7; color: var(--text-main); margin-bottom: 25px; font-weight: 500; letter-spacing: -0.5px;">
            마법사 청킹<span style="font-family:'Poppins'; font-weight:700; color: var(--primary); margin-left: 2px;">Chunking</span>과 함께<br>
            영어단어 영어문법 영어어순이 결합되어 있는<br>
            <strong style="color: var(--text-main); font-size: 1.25rem; font-weight: 800; display: inline-block; margin-top: 5px; background: linear-gradient(to top, rgba(255, 206, 84, 0.6) 40%, transparent 40%); padding: 0 5px;">의미덩어리 청킹<span style="font-family:'Poppins';">Chunking</span>을 반복 숙달하여</strong>
        </p>

        <div style="margin-bottom: 25px; padding: 10px;">
            <p style="font-size: 1.2rem; font-weight: 800; line-height: 1.6; color: var(--primary); letter-spacing: -0.5px; margin-bottom: 0;">
                우리 아이의 영어미래를 활짝 열어주고<br>
                나의 영어세계를 더 높이 더 넓게 펼쳐가세요.
            </p>
        </div>

        <p style="font-size: 1.3rem; font-weight: 900; color: var(--text-main); margin-bottom: 30px; letter-spacing: -0.5px;">
            의미덩어리 청킹<span style="font-family:'Poppins'; font-weight:800;">Chunking</span>으로...<br>
            <span style="color: var(--primary);">영어로... 자유롭고 유창하게... 글로벌 세상과 소통하세요^^</span>
        </p>

        <div style="margin-top: auto; padding-top: 20px; border-top: 1px dashed #E2E8F0; text-align: center;">
            <p style="font-family: 'Pretendard', sans-serif; font-size: 0.85rem; color: var(--text-sub); line-height: 1.6; margin: 0; font-weight: 400; word-break: keep-all;">
                <strong style="color: var(--text-main); font-weight: 700;">ⓒ 저작권 안내</strong> | 이 책에 실린 내용, 이미지, 소리, 음원, 디자인, 편집 구성의 저작권은 저자에게 있습니다.<br>
                상업적 사용목적으로 허락 없이 복제하거나 함부로 사용할 경우 민형사상 책임을 질 수 있습니다.<br>
                개인 학습의 경우, 출처 ‘청킹으로 쉽게 영어말하기’를 밝히면 언제 어디서나 저작권 제한 없이 사용 가능합니다.
            </p>
        </div>
    </div>
</div>

</body>
</html>