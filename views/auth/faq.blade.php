<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Pertanyaan yang Sering Diajukan | {{ config('app.name') }} DPR RI</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">

    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
        }

        .bg-pattern {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
            background-size: cover;
            opacity: 0.05;
            z-index: -1;
        }
    </style>

    {{-- Header & footer chrome (CSS + skin toggle) bersama dengan halaman beranda --}}
    @include('auth.partials.public-chrome-head')

    <style>
        /* === Konten FAQ === */
        .faq-hero {
            text-align: center;
            padding: 3.5rem 6% 1rem;
            margin-top: 72px; /* offset header fixed */
        }
        .faq-hero .tag {
            display: inline-block;
            background: rgba(176, 141, 72, 0.12);
            color: var(--gold-solid);
            font-weight: 800;
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 1rem;
        }
        .faq-hero h1 { font-size: 2.2rem; font-weight: 800; color: var(--primary-dark); margin: 0 0 0.6rem; }
        .faq-hero p { color: var(--text-muted); max-width: 620px; margin: 0 auto; }

        /* Search */
        .faq-search {
            max-width: 560px;
            margin: 1.75rem auto 0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.8rem 1.1rem;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            transition: var(--transition);
        }
        .faq-search:focus-within {
            border-color: var(--gold-solid);
            box-shadow: 0 6px 20px rgba(176, 141, 72, 0.15);
        }
        .faq-search > i { color: var(--gold-solid); font-size: 1.2rem; }
        .faq-search input {
            flex: 1;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 1rem;
            background: transparent;
            color: var(--text-main);
        }
        .faq-search button {
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }
        .faq-search button:hover { color: var(--gold-solid); }

        .faq-wrapper {
            max-width: 820px;
            width: 90%;
            margin: 2rem auto 4rem;
        }

        .faq-item {
            background: var(--white);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        }
        .faq-item.open { border-color: var(--gold-solid); box-shadow: 0 8px 20px rgba(176, 141, 72, 0.12); }

        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 1.2rem 1.5rem;
            cursor: pointer;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.02rem;
            user-select: none;
        }
        .faq-question .icon {
            flex-shrink: 0;
            width: 30px; height: 30px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: rgba(176, 141, 72, 0.12);
            color: var(--gold-solid);
            transition: var(--transition);
        }
        .faq-item.open .faq-question .icon { transform: rotate(180deg); background: var(--gold-solid); color: #fff; }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding: 0 1.5rem;
            color: var(--text-main);
        }
        .faq-answer .inner { padding: 0 0 1.3rem; color: #475569; }

        .faq-empty, .faq-no-results {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--text-muted);
        }
        .faq-empty i, .faq-no-results i { font-size: 3rem; color: #cbd5e1; }

        /* === Dark mode konten FAQ === */
        html[data-skin="dark"] .faq-hero h1 { color: #ffffff; }
        html[data-skin="dark"] .faq-hero p { color: #cbd5e1; }
        html[data-skin="dark"] .faq-item { background: #0f172a; border-color: #1f2937; }
        html[data-skin="dark"] .faq-item.open { border-color: #fbbf24; }
        html[data-skin="dark"] .faq-question { color: #ffffff; }
        html[data-skin="dark"] .faq-answer .inner { color: #cbd5e1; }
        html[data-skin="dark"] .faq-search { background: #0f172a; border-color: #1f2937; }
        html[data-skin="dark"] .faq-search input { color: #ffffff; }
        html[data-skin="dark"] .faq-no-results, html[data-skin="dark"] .faq-empty { color: #94a3b8; }

        @media (max-width: 576px) {
            .faq-hero h1 { font-size: 1.6rem; }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    @include('auth.partials.public-header')

    <section class="faq-hero">
        <span class="tag">Pusat Bantuan</span>
        <h1>Pertanyaan yang Sering Diajukan</h1>
        <p>Temukan jawaban atas pertanyaan umum seputar program magang di DPR RI. Jika belum menemukan jawaban, silakan hubungi kami.</p>

        <div class="faq-search">
            <i class="ri-search-line"></i>
            <input type="text" id="faqSearchInput" placeholder="Cari pertanyaan..." autocomplete="off" aria-label="Cari pertanyaan">
            <button type="button" id="faqSearchClear" aria-label="Hapus pencarian" style="display:none;"><i class="ri-close-line"></i></button>
        </div>
    </section>

    <main class="faq-wrapper">
        <div id="faqList">
            @forelse($faqs as $faq)
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>{{ $faq->pertanyaan }}</span>
                        <span class="icon"><i class="ri-arrow-down-s-line"></i></span>
                    </div>
                    <div class="faq-answer">
                        <div class="inner">{!! nl2br(e($faq->jawaban)) !!}</div>
                    </div>
                </div>
            @empty
                <div class="faq-empty">
                    <i class="ri-question-answer-line"></i>
                    <p class="mt-3" style="margin-top:1rem; font-weight:700;">Belum ada pertanyaan yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

        <div id="faqNoResults" class="faq-no-results" style="display:none;">
            <i class="ri-search-eye-line"></i>
            <p style="margin-top:1rem; font-weight:700;">Tidak ada pertanyaan yang cocok dengan pencarian Anda.</p>
        </div>
    </main>

    @include('auth.partials.public-footer')

    @include('auth.partials.public-chrome-scripts')

    <script>
        function toggleFaq(el) {
            const item = el.closest('.faq-item');
            const answer = item.querySelector('.faq-answer');
            if (item.classList.contains('open')) {
                item.classList.remove('open');
                answer.style.maxHeight = null;
            } else {
                item.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        }

        // Pencarian FAQ (filter client-side berdasarkan pertanyaan & jawaban)
        (function initFaqSearch() {
            const input = document.getElementById('faqSearchInput');
            const clearBtn = document.getElementById('faqSearchClear');
            const noResults = document.getElementById('faqNoResults');
            if (!input) return;

            const items = Array.from(document.querySelectorAll('#faqList .faq-item'));

            function normalize(s) { return (s || '').toLowerCase().trim(); }

            function applyFilter() {
                const q = normalize(input.value);
                if (clearBtn) clearBtn.style.display = q ? 'flex' : 'none';

                let visible = 0;
                items.forEach(function (item) {
                    const question = normalize(item.querySelector('.faq-question span') ? item.querySelector('.faq-question span').textContent : '');
                    const answer = normalize(item.querySelector('.faq-answer .inner') ? item.querySelector('.faq-answer .inner').textContent : '');
                    const match = !q || question.indexOf(q) !== -1 || answer.indexOf(q) !== -1;
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });

                if (noResults) noResults.style.display = (items.length > 0 && visible === 0) ? '' : 'none';
            }

            input.addEventListener('input', applyFilter);
            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    input.value = '';
                    applyFilter();
                    input.focus();
                });
            }
        })();
    </script>
</body>
</html>
