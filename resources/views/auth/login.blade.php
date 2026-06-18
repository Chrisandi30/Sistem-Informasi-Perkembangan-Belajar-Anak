@php
    // View: resources/views/auth/login.blade.php
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --background: #7f1d1d;
        }
        * { box-sizing: border-box; }
        body.page-loading { cursor: progress; }
        .app-loading-overlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(242, 242, 245, 0.76);
            backdrop-filter: blur(3px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .18s ease, visibility .18s ease;
            z-index: 9999;
        }
        .app-loading-overlay.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .app-loading-box {
            min-width: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 22px 26px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #e5e7eb;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
        }
        .app-loading-spinner {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 4px solid #e5e7eb;
            border-top-color: var(--background);
            animation: appSpin .85s linear infinite;
        }
        .app-loading-text {
            font-size: 14px;
            font-weight: 700;
            color: #1d1d23;
            text-align: center;
        }
        .toggle-btn:hover {
            background: #f3eefc;
        }
        @keyframes appSpin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="m-0 grid min-h-screen place-items-center bg-[#f2f2f5] px-6 py-6 font-['Inter',sans-serif] text-[#1d1d23] md:px-5 sm:px-[14px]">
<div id="appLoadingOverlay" class="app-loading-overlay" aria-hidden="true">
    <div class="app-loading-box">
        <div class="app-loading-spinner"></div>
        <div id="appLoadingText" class="app-loading-text">Sedang masuk...</div>
    </div>
</div>

<div class="w-full max-w-[440px] rounded-2xl border border-[#dedee6] bg-white px-9 pb-8 pt-10 shadow-[0_10px_30px_rgba(19,20,24,0.06)] md:px-7 md:pb-7 md:pt-9 sm:rounded-xl sm:px-6 sm:pb-6 sm:pt-8">
    <div class="text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo TK" class="mx-auto mb-1 block w-full max-w-[156px]">
        <h1 class="mt-1 text-[22px] font-bold tracking-[-0.02em] text-[#1d1d23]">Selamat Datang!</h1>
        <p class="mt-1 text-[15px] font-medium italic text-[#7b8190]">Silahkan Login</p>
    </div>

    <div id="appAuthMeta" data-success='@json(session("success"))' data-errors='@json($errors->all())' hidden></div>

    <form method="post" action="{{ route('login.attempt') }}" class="auth-login-form mt-7 space-y-4">
        @csrf

        <div>
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#8b93a3]">
                    <i class="fa-solid fa-user"></i>
                </span>
                <span class="pointer-events-none absolute left-[34px] top-1/2 h-5 w-px -translate-y-1/2 bg-[#d7dce6]" aria-hidden="true"></span>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Username"
                    required
                    class="h-[52px] w-full rounded-[10px] border border-[#d9d9e1] bg-white pl-[42px] pr-4 text-base text-[#1d1d23] outline-none transition focus:border-[#7f1d1d] focus:ring-4 focus:ring-[rgba(127,29,29,0.12)]"
                >
            </div>
        </div>

        <div>
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#8b93a3]">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <span class="pointer-events-none absolute left-[34px] top-1/2 h-5 w-px -translate-y-1/2 bg-[#d7dce6]" aria-hidden="true"></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="h-[52px] w-full rounded-[10px] border border-[#d9d9e1] bg-white pl-[42px] pr-12 text-base text-[#1d1d23] outline-none transition focus:border-[#7f1d1d] focus:ring-4 focus:ring-[rgba(127,29,29,0.12)]"
                >
                <button
                    type="button"
                    class="toggle-btn absolute right-2 top-1/2 flex h-[30px] -translate-y-1/2 items-center justify-center rounded-full px-2 text-xs font-semibold text-[#6d6d78] transition"
                    id="togglePassword"
                    aria-label="Tampilkan password"
                >
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 pt-1 text-sm text-[#5d5d68]">
            <label class="m-0 inline-flex items-center gap-2 leading-none text-inherit">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="h-4 w-4 rounded border-[#d2d6df] text-[#7f1d1d] focus:ring-[#7f1d1d]">
                <span>Remember me</span>
            </label>
        </div>

        <div class="pt-1">
            <button
                type="submit"
                class="mx-auto block h-[52px] w-full max-w-[200px] rounded-full border-0 bg-[#7f1d1d] text-[17px] font-semibold text-white transition hover:brightness-105 active:translate-y-px"
            >
                Login
            </button>
        </div>

        @if(session('success'))
            <div class="hidden rounded-lg border border-[#b9ead0] bg-[#eefcf3] px-3 py-2.5 text-[13px] text-[#1f7a44] success-box">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-lg border border-[#ffc8c8] bg-[#fff3f3] px-3 py-2.5 text-[13px] text-[#a12a2a] error-box">{{ $errors->first() }}</div>
        @endif
    </form>
</div>

@include('partials.scripts.app-auth-ui')
</body>
</html>