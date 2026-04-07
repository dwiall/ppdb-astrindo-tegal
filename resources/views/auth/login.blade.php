<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin PPDB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background-image: url('{{ asset('assets/logo/astrindo.jpeg') }}');
            background-size: cover;
            background-position: center 20%;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            position: relative;
        }

        /* Overlay gradient agar tulisan gedung tetap terlihat */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0.05),
                rgba(0, 0, 0, 0.35)
            );
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            position: relative;
            z-index: 2;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-logo h4 {
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
        }

        .btn-login {
            background: #1e3a8a;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-login:hover {
            background: #1e40af;
        }

        .footer-text {
            text-align: center;
            margin-top: 18px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-logo">
        <h4>Admin PPDB</h4>
        <p>SMK Astrindo Tegal</p>
    </div>

    {{-- 🔴 NOTIFIKASI LOGIN GAGAL --}}
    @if ($errors->has('email'))
        <div class="alert alert-danger">
            <strong>Login gagal!</strong><br>
            Email atau password yang Anda masukkan tidak benar.
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-login text-white">
                Login
            </button>
        </div>
    </form>

    <div class="footer-text">
        Sistem Analisis & Prediksi PPDB
    </div>
</div>

</body>
</html>
