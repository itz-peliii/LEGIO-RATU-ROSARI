<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar · Paroki Santo Petrus Tuban</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Nunito+Sans:wght@400;600;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
/* =========================================================
   TOKENS (sama dengan landing page & halaman login)
   ========================================================= */
.auth {
    --ink: #16233f;
    --ink-soft: #22345c;
    --gold: #b98a3e;
    --gold-soft: #e6cf9b;
    --paper: #f3f6fa;
    --line: #d9dfeb;
    --text: #2b3550;
    --muted: #66718c;
    --danger: #b3261e;
    --shadow: 0 22px 44px -22px rgba(22, 35, 63, .38);
    --display: 'Cormorant Garamond', Georgia, serif;
    --body: 'Nunito Sans', system-ui, sans-serif;
    --ease: cubic-bezier(.2, .7, .2, 1);
    --r: 18px;
    /* ukuran fluida: menyusut mengikuti TINGGI layar agar muat di laptop 1366×768 */
    --gap: clamp(.55rem, 1.7vh, 1rem);
    --in-h: clamp(2.6rem, 6vh, 3.1rem);

    font-family: var(--body);
    color: var(--text);
    min-height: 100vh;
    min-height: 100dvh;
    display: grid;
    background: var(--paper);
}
.auth *, .auth *::before, .auth *::after { box-sizing: border-box; }
.auth ::selection { background: var(--gold-soft); color: var(--ink); }
.auth .cross { width: 14px; height: auto; fill: currentColor; flex: none; }
.auth .sr-only {
    position: absolute; width: 1px; height: 1px; margin: -1px; padding: 0;
    overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
}

/* Desktop / laptop: tepat satu layar. Jika layar sangat pendek (<560px)
   tinggi dibebaskan supaya form tidak pernah terpotong. */
@media (min-width: 1024px) {
    .auth { grid-template-columns: 1.05fr 1fr; }
}
@media (min-width: 1024px) and (min-height: 560px) {
    .auth { height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; }
}

/* =========================================================
   PANEL KIRI — visual gereja
   ========================================================= */
.auth .visual {
    position: relative; overflow: hidden; isolation: isolate;
    display: flex; align-items: center; justify-content: space-between;
    color: #fff; background: var(--ink);
    padding: 1.1rem 1.25rem 2.6rem;
}
.auth .visual-img {
    position: absolute; inset: 0; z-index: -2;
    background: url('{{ asset('assets/images/profil/gereja-depan.png') }}') center / cover no-repeat;
    transform: scale(1.04);
    animation: auth-drift 14s ease-out forwards;
}
@keyframes auth-drift { from { transform: scale(1); } to { transform: scale(1.07); } }
.auth .visual-shade {
    position: absolute; inset: 0; z-index: -1;
    background:
        linear-gradient(to top, rgba(12,21,43,.95) 0%, rgba(12,21,43,.62) 45%, rgba(12,21,43,.35) 100%),
        linear-gradient(to bottom, rgba(12,21,43,.55) 0%, rgba(12,21,43,0) 30%);
}
.auth .visual-arch {
    position: absolute; z-index: -1; right: -2.5rem; bottom: 0; width: clamp(9rem, 24vh, 14rem);
    color: var(--gold); opacity: .28; pointer-events: none; display: none;
}

.auth .brand { display: inline-flex; align-items: center; gap: .85rem; color: #fff; text-decoration: none; width: fit-content; }
.auth .brand-mark {
    width: 2.5rem; height: 2.5rem; border-radius: 50%;
    display: grid; place-items: center; color: var(--gold-soft);
    border: 1px solid rgba(230,207,155,.55); background: rgba(255,255,255,.08);
    backdrop-filter: blur(6px);
}
.auth .brand-mark .cross { width: 12px; }
.auth .brand-name { font-family: var(--display); font-weight: 600; font-size: 1.25rem; line-height: 1.1; }
.auth .brand-name small {
    display: block; font-family: var(--body); font-size: .66rem; font-weight: 700;
    letter-spacing: .22em; text-transform: uppercase; color: var(--gold-soft); margin-top: .2rem;
}

.auth .visual-copy, .auth .visual-foot { display: none; }
.auth .visual-copy { max-width: 100%; margin-top: -4rem; }
.auth .visual-copy .eyebrow {
    display: inline-flex; align-items: center; gap: .75rem;
    font-size: .76rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase;
    color: var(--gold-soft); margin-bottom: 1rem;
}
.auth .visual-copy .eyebrow::before { content: ''; width: 2.25rem; height: 1px; background: currentColor; }
.auth .visual-copy h2 {
    margin: 0; font-family: var(--display); font-weight: 600; line-height: 1.05; letter-spacing: -.01em;
    font-size: clamp(2.5rem, min(4.2vw, 7.2vh), 3.8rem);
    text-wrap: balance;
    text-shadow: 0 2px 30px rgba(0,0,0,.35);
}
.auth .visual-copy h2::after { content: ''; display: block; width: 3.5rem; height: 3px; background: var(--gold); margin-top: clamp(.8rem, 2.4vh, 1.25rem); }
.auth .visual-copy p { margin: clamp(.8rem, 2.4vh, 1.25rem) 0 0; font-size: clamp(.95rem, 2.2vh, 1.05rem); line-height: 1.65; color: rgba(255,255,255,.86); }
.auth .visual-foot { font-size: .84rem; color: rgba(255,255,255,.65); letter-spacing: .02em; }

@media (min-width: 1024px) {
    .auth .visual {
        flex-direction: column; align-items: flex-start; justify-content: space-between;
        padding: clamp(1.25rem, 4.5vh, 2.5rem) clamp(2rem, 4vw, 3rem);
    }
    .auth .visual-copy, .auth .visual-foot { display: block; }
    .auth .visual-arch { display: block; }
}

/* =========================================================
   PANEL KANAN — form
   ========================================================= */
.auth .panel {
    position: relative; display: flex; align-items: center; justify-content: center;
    padding: 0 1rem 1.5rem; background: var(--paper); overflow: hidden;
}
@media (min-width: 1024px) { .auth .panel { padding: clamp(.75rem, 3vh, 2.5rem) 2.5rem; } }

.auth .panel::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: radial-gradient(rgba(22,35,63,.12) 1.2px, transparent 1.5px);
    background-size: 26px 26px;
    -webkit-mask-image: radial-gradient(ellipse at center, transparent 30%, #000 90%);
            mask-image: radial-gradient(ellipse at center, transparent 30%, #000 90%);
}
.auth .panel::after {
    content: ''; position: absolute; width: 28rem; height: 28rem; top: -10rem; right: -9rem; border-radius: 50%;
    background: radial-gradient(circle, rgba(230,207,155,.8), rgba(230,207,155,0) 70%);
    filter: blur(60px); opacity: .6; pointer-events: none;
}

.auth .card {
    position: relative; z-index: 1; width: 100%; max-width: 27rem;
    background: #fff; border: 1px solid var(--line); border-radius: var(--r);
    box-shadow: var(--shadow); overflow: hidden;
    padding: 1.5rem 1.35rem 1.25rem;
    margin-top: -1.6rem; /* menumpuk halus ke banner di mobile */
    animation: auth-rise .9s var(--ease) both;
}
@media (min-width: 1024px) {
    .auth .card { margin-top: 0; padding: clamp(1.25rem, 4.2vh, 2.4rem) clamp(1.75rem, 2.6vw, 2.5rem) clamp(1rem, 3vh, 1.75rem); }
}
@keyframes auth-rise { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
.auth .card::before { content: ''; position: absolute; left: 0; right: 0; top: 0; height: 3px; background: var(--gold); }

/* ---------- field ---------- */
.auth .field + .field { margin-top: var(--gap); }
.auth .label {
    display: block; margin-bottom: .36rem;
    font-size: .72rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--ink-soft);
}
.auth .control { position: relative; }
.auth .control svg.ic {
    position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
    width: 1.1rem; height: 1.1rem; color: var(--muted); pointer-events: none; transition: color .25s;
}
.auth .input {
    display: block; width: 100%; height: var(--in-h); padding: 0 1rem 0 2.9rem;
    font: inherit; font-size: 1rem; color: var(--ink);
    background: var(--paper); border: 1px solid var(--line); border-radius: 12px;
    transition: border-color .25s, box-shadow .25s, background .25s;
}
.auth .input::placeholder { color: #9aa4bb; }
.auth .input:hover { border-color: #bcc6da; }
.auth .input:focus { outline: none; background: #fff; border-color: var(--gold); box-shadow: 0 0 0 4px rgba(185,138,62,.18); }
.auth .control:focus-within svg.ic { color: var(--gold); }
.auth .input[aria-invalid="true"] { border-color: var(--danger); background: #fff8f7; }
.auth .input[aria-invalid="true"]:focus { box-shadow: 0 0 0 4px rgba(179,38,30,.14); }
.auth .input.has-toggle { padding-right: 3.1rem; }

.auth .toggle {
    position: absolute; right: .4rem; top: 50%; transform: translateY(-50%);
    width: 2.4rem; height: 2.4rem; display: grid; place-items: center;
    border: 0; border-radius: 10px; background: transparent; color: var(--muted); cursor: pointer;
    transition: background .2s, color .2s;
}
.auth .toggle:hover { background: rgba(22,35,63,.06); color: var(--ink); }
.auth .toggle svg { width: 1.15rem; height: 1.15rem; }
.auth .toggle .eye-off { display: none; }
.auth .toggle[aria-pressed="true"] .eye { display: none; }
.auth .toggle[aria-pressed="true"] .eye-off { display: block; }

/* pesan error (komponen Breeze) */
.auth .field-error { margin-top: .35rem; font-size: .84rem; }
.auth .field-error ul { color: var(--danger) !important; margin: 0; padding: 0; list-style: none; }

.auth .link {
    color: var(--ink-soft); font-size: .92rem; font-weight: 600; text-decoration: underline;
    text-decoration-color: var(--gold-soft); text-underline-offset: 4px; border-radius: 6px;
    transition: color .2s, text-decoration-color .2s;
}
.auth .link:hover { color: var(--gold); text-decoration-color: var(--gold); }

/* ---------- tombol ---------- */
.auth .btn-gold {
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    width: 100%; margin-top: clamp(.9rem, 2.6vh, 1.4rem); height: var(--in-h); padding: 0 2rem; border: 0; cursor: pointer;
    font: inherit; font-weight: 700; letter-spacing: .01em; color: #fff;
    background: linear-gradient(135deg, #c79646, var(--gold) 55%, #a87c33);
    border-radius: 999px; box-shadow: 0 10px 24px -10px rgba(185,138,62,.8);
    transition: filter .25s, transform .25s, box-shadow .25s;
}
.auth .btn-gold:hover { filter: brightness(1.07); transform: translateY(-2px); box-shadow: 0 16px 30px -12px rgba(185,138,62,.9); }
.auth .btn-gold:active { transform: translateY(0); }
.auth .btn-gold svg { width: 1.05rem; height: 1.05rem; transition: transform .25s var(--ease); }
.auth .btn-gold:hover svg { transform: translateX(3px); }
.auth .btn-gold[disabled] { filter: grayscale(.2) brightness(.95); cursor: progress; transform: none; }

/* ---------- masuk + footer kartu (keduanya di tengah) ---------- */
.auth .signup {
    margin: clamp(.65rem, 1.9vh, 1rem) 0 0; text-align: center;
    font-size: .9rem; color: var(--muted);
}
.auth .signup .link { font-size: .9rem; font-weight: 700; }
.auth .card-foot {
    margin-top: clamp(.75rem, 2.1vh, 1.2rem); padding-top: clamp(.65rem, 1.9vh, 1.1rem); border-top: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center; text-align: center;
    font-size: .88rem; color: var(--muted);
}
.auth .card-foot a.link { display: inline-flex; align-items: center; gap: .4rem; font-size: .88rem; }
.auth .card-foot svg { width: .95rem; height: .95rem; }

/* ---------- fokus & aksesibilitas ---------- */
.auth a:focus-visible, .auth button:focus-visible { outline: 3px solid var(--gold-soft); outline-offset: 3px; }
.auth .btn-gold:focus-visible { outline-color: var(--ink-soft); }

@media (prefers-reduced-motion: reduce) {
    .auth .visual-img, .auth .card { animation: none; }
    .auth .btn-gold, .auth .btn-gold svg, .auth .input { transition: none; }
}
</style>
</head>

<body class="antialiased" style="margin:0; background:#f3f6fa;">
<div class="auth">

    <!-- ===================== PANEL VISUAL ===================== -->
    <aside class="visual" aria-label="Paroki Santo Petrus Tuban">
        <div class="visual-img" role="presentation"></div>
        <div class="visual-shade"></div>
        <svg class="visual-arch" viewBox="0 0 200 360" aria-hidden="true">
            <path d="M10 360V110a90 90 0 0 1 180 0V360" fill="none" stroke="currentColor" stroke-width="2"/>
            <path d="M34 360V112a66 66 0 0 1 132 0V360" fill="none" stroke="currentColor" stroke-width="1.2"/>
            <path d="M100 42v56M76 62h48" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>

        <a href="{{ url('/') }}" class="brand" aria-label="Kembali ke beranda Paroki Santo Petrus Tuban">
            <span class="brand-mark" aria-hidden="true">
                <svg class="cross" viewBox="0 0 24 32"><path d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z"/></svg>
            </span>
            <span class="brand-name">Santo Petrus
                <small>Paroki Tuban</small>
            </span>
        </a>

        <div class="visual-copy">
            <span class="eyebrow">Bergabung Bersama Kami</span>
            <h2>Bersama dalam iman, terhubung dalam pelayanan.</h2>
            <p>Daftarkan akun Anda untuk terhubung dengan layanan Paroki Santo Petrus Tuban.</p>
        </div>

        <p class="visual-foot">Gereja Santo Petrus · Tuban, Jawa Timur &nbsp;·&nbsp; &copy; {{ date('Y') }}</p>
    </aside>

    <!-- ===================== PANEL FORM ===================== -->
    <main class="panel">
        <section class="card" aria-labelledby="register-title">
            <h1 id="register-title" class="sr-only">Daftar Akun Baru</h1>

            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <!-- Name -->
                <div class="field">
                    <label for="name" class="label">{{ __('Name') }}</label>
                    <div class="control">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="3.6"/><path d="M4.5 20a7.5 7.5 0 0 1 15 0"/>
                        </svg>
                        <input id="name" class="input" type="text" name="name"
                               value="{{ old('name') }}" placeholder="Nama lengkap"
                               required autofocus autocomplete="name"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="field-error" />
                </div>

                <!-- Email Address -->
                <div class="field">
                    <label for="email" class="label">{{ __('Email') }}</label>
                    <div class="control">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3.5 7l8.5 6 8.5-6"/>
                        </svg>
                        <input id="email" class="input" type="email" name="email"
                               value="{{ old('email') }}" placeholder="nama@email.com"
                               required autocomplete="username"
                               aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="field-error" />
                </div>

                <!-- Password -->
                <div class="field">
                    <label for="password" class="label">{{ __('Password') }}</label>
                    <div class="control">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4" y="10.5" width="16" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/>
                        </svg>
                        <input id="password" class="input has-toggle" type="password" name="password"
                               placeholder="Minimal 8 karakter"
                               required autocomplete="new-password"
                               aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                        <button type="button" class="toggle" aria-pressed="false" aria-controls="password" aria-label="Tampilkan password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 3l18 18"/><path d="M10.6 5.1A10.7 10.7 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.5 6.5A17 17 0 0 0 2 12s3.6 7 10 7c1.7 0 3.2-.4 4.5-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="field-error" />
                </div>

                <!-- Confirm Password -->
                <div class="field">
                    <label for="password_confirmation" class="label">{{ __('Confirm Password') }}</label>
                    <div class="control">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4" y="10.5" width="16" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/>
                        </svg>
                        <input id="password_confirmation" class="input has-toggle" type="password" name="password_confirmation"
                               placeholder="Ulangi password"
                               required autocomplete="new-password"
                               aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}">
                        <button type="button" class="toggle" aria-pressed="false" aria-controls="password_confirmation" aria-label="Tampilkan password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 3l18 18"/><path d="M10.6 5.1A10.7 10.7 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.5 6.5A17 17 0 0 0 2 12s3.6 7 10 7c1.7 0 3.2-.4 4.5-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="field-error" />
                </div>

                <button type="submit" class="btn-gold" id="register-btn">
                    <span>{{ __('Register') }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>

            <p class="signup">Sudah punya akun? <a class="link" href="{{ route('login') }}">Masuk</a></p>

            <footer class="card-foot">
                <a href="{{ url('/') }}" class="link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                    Kembali ke beranda
                </a>
            </footer>
        </section>
    </main>
</div>

<script>
(function () {
    // Tampilkan / sembunyikan password (untuk kedua kolom password)
    document.querySelectorAll('.auth .toggle').forEach(function (tg) {
        var input = document.getElementById(tg.getAttribute('aria-controls'));
        if (!input) return;
        tg.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            tg.setAttribute('aria-pressed', show ? 'true' : 'false');
            tg.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });
    // Cegah klik ganda saat form dikirim
    var form = document.getElementById('register-form');
    var btn = document.getElementById('register-btn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            if (form.checkValidity()) { btn.setAttribute('disabled', 'disabled'); }
        });
    }
})();
</script>
</body>
</html>