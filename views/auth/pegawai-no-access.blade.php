<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tidak Memiliki Akses | {{ config('app.name') }} DPR RI</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { max-width: 100%; overflow-x: hidden; }
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0; padding: 0;
            background-color: #f8fafc;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.7;
        }
        /* Dark mode versi public page */
        html[data-skin="dark"] body {
            background-color: #020617;
            color: #e5e7eb;
        }

        /* Background Motif Batik */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}'); 
            background-size: 400px;
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }

        header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 0.8rem 6%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: -12px;
            text-decoration: none;
        }

        .logo-container img {
            height: 45px;
            width: auto;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .logo-text span {
            display: block;
            font-size: 0.7rem;
            color: var(--gold-solid);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 40px;
        }

        /* Main Card */
        .main-content {
            width: 100%;
            max-width: 550px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        html[data-skin="dark"] .main-content {
            background: #0f172a;
            border-color: #1f2937;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        .main-content::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .icon-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fecaca;
        }

        .icon-container i {
            font-size: 3.5rem;
            color: var(--danger);
        }

        h2 {
            font-weight: 800;
            margin-bottom: 15px;
            color: var(--primary-dark);
            font-size: 1.8rem;
        }
        html[data-skin="dark"] h2 { color: #e5e7eb; }

        .message {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        html[data-skin="dark"] .message { color: #9ca3af; }

        .info-box {
            background: #f1f5f9;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        html[data-skin="dark"] .info-box {
            background: #1e293b;
            border-color: #374151;
            color: #e5e7eb;
        }

        .info-box label {
            display: block;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box .value {
            font-size: 1rem;
            color: var(--primary-dark);
            font-weight: 600;
        }

        .btn-logout {
            background: var(--accent-gold);
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(142, 109, 47, 0.2);
            text-decoration: none;
            display: inline-block;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(142, 109, 47, 0.3);
        }

        .btn-logout i {
            margin-right: 8px;
        }

        @media (max-width: 480px) {
            header { padding: 0.6rem 4%; }
            .logo-container img { height: 38px !important; }
            .logo-text { font-size: 1rem; }
            .logo-text span { font-size: 0.6rem; letter-spacing: 1px; }
            .main-content { padding: 28px 20px; }
            h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern"></div>
    
    <header>
        <a href="{{ route('Halaman awal') }}" class="logo-container">
            <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/SMART.png') }}" alt="Logo {{ config('app.name') }}" style="height: 50px;">
            <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI" style="height: 50px; margin-right: 5px;">
            <div class="logo-text">
                {{ config('app.name') }}
                <span>Setjen DPR RI</span>
            </div>
        </a>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="icon-container">
                <i class="fa-solid fa-ban"></i>
            </div>
            
            <h2>Akses Ditolak</h2>
            
            <p class="message">
                Anda tidak memiliki role akses ke dashboard. Silahkan logout.
            </p>

            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
