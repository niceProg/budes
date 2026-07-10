<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview PDF - {{ $materi->judul }}</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg: #f8fafc;
            --panel: rgba(255,255,255,0.9);
            --border: rgba(226,232,240,0.9);
            --text: #0f172a;
            --muted: #64748b;
            --gold: #b08d48;
            --shadow: 0 20px 40px rgba(15,23,42,0.12);
        }
        html, body { height: 100%; }
        body{
            margin:0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow: hidden;
        }
        .bg-pattern{
            position: fixed;
            inset: 0;
            background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
            background-size: 420px;
            opacity: 0.04;
            pointer-events:none;
            z-index: 0;
        }
        .viewer{
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }
        .viewer iframe{
            width: 100%;
            height: 100%;
            border: 0;
            background: #fff;
        }

        /* Dark mode */
        html[data-skin="dark"] body { background: #020617; color: #fff; }
        html[data-skin="dark"] .bg-pattern{ opacity: 0.03; }
        html[data-skin="dark"] .viewer iframe{ background: #0b1220; }
    </style>
    <script>
        (function () {
            try {
                var mode = localStorage.getItem('skin-mode');
                if (mode === 'dark') document.documentElement.setAttribute('data-skin', 'dark');
                else document.documentElement.setAttribute('data-skin', 'light');
            } catch (e) {}
        })();
    </script>
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern"></div>

    <div class="viewer">
        <iframe src="{{ $pdfUrl }}" loading="lazy"></iframe>
    </div>
</body>
</html>

