<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Petugas - SMK Nusantara</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #111827;
            -webkit-font-smoothing: antialiased;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 410px;
            border-radius: 20px;
            padding: 44px 36px 36px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02), 0 0 0 1px rgba(0, 0, 0, 0.03);
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Brand / Header */
        .brand-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .brand-icon-circle {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background-color: #c7f2e8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }

        .brand-icon-circle svg {
            color: #0d9488;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.02em;
            margin-bottom: 5px;
            text-align: center;
        }

        .brand-subtitle {
            font-size: 13.5px;
            color: #6b7280;
            font-weight: 400;
            text-align: center;
        }

        /* Alerts */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #dcfce7;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .forgot-link {
            font-size: 12.5px;
            font-weight: 500;
            color: #0d9488;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: #0b786d;
            text-decoration: underline;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            height: 44px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 14px;
            color: #1f2937;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background-color: #fffafa;
        }

        .input-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease;
        }

        .input-wrapper .toggle-password:hover {
            color: #374151;
        }

        .input-with-icon {
            padding-right: 42px;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 44px;
            background-color: #0d9488;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 24px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-submit:hover {
            background-color: #0b786d;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        /* Demo badge helper */
        .demo-box {
            margin-top: 24px;
            padding: 12px 14px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
        }

        .demo-box strong {
            color: #0f172a;
        }

        .demo-box .quick-fill {
            display: inline-block;
            margin-top: 6px;
            color: #0d9488;
            font-weight: 600;
            text-decoration: underline;
            cursor: pointer;
        }

        /* Modal for Lupa Password */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            max-width: 380px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: modalFadeIn 0.2s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(8px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .modal-text {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .btn-modal-close {
            width: 100%;
            height: 40px;
            background: #0d9488;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-modal-close:hover {
            background: #0b786d;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Header / Logo -->
        <div class="brand-header">
            <div class="brand-icon-circle">
                <img src="{{ asset('assets/images/Logo Sekolah_No_Bg.png') }}" alt="Logo SMK Negeri 1 Tirtamulya" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h1 class="brand-title">SMK Negeri 1 Tirtamulya</h1>
            <p class="brand-subtitle">Sistem Perpustakaan Digital</p>
        </div>

        <!-- Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
            @csrf

            <!-- Username / Email Input -->
            <div class="form-group">
                <label for="login" class="form-label">Username / Email</label>
                <div class="input-wrapper">
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        class="form-input {{ $errors->has('login') ? 'is-invalid' : '' }}" 
                        placeholder="admin_smk" 
                        value="{{ old('login') }}" 
                        required 
                        autofocus
                    >
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <div class="label-row">
                    <label for="password" class="form-label" style="margin-bottom: 0;">Kata Sandi</label>
                    <a href="javascript:void(0)" class="forgot-link" onclick="openForgotModal()">Lupa?</a>
                </div>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input input-with-icon {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                        placeholder="••••••••••••" 
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Lihat kata sandi">
                        <!-- Eye icon -->
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <!-- Eye off icon (hidden initially) -->
                        <svg id="eye-off-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                Masuk
            </button>
        </form>

        <!-- Demo Account Info Box -->
        <!-- <div class="demo-box">
            Akses Petugas: <strong>admin_smk</strong> &bull; Kata Sandi: <strong>password</strong><br>
            <span class="quick-fill" onclick="fillDemo()">Klik di sini untuk isi otomatis</span>
        </div> -->
    </div>

    <!-- Modal Lupa Sandi -->
    <div id="forgot-modal" class="modal-backdrop" onclick="closeForgotModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h3 class="modal-title">Lupa Kata Sandi?</h3>
            <p class="modal-text">
                Untuk keamanan data perpustakaan, silakan hubungi <strong>Kepala Perpustakaan</strong> atau tim <strong>Administrator IT SMK Nusantara</strong> untuk melakukan pengaturan ulang kata sandi akun Anda.
            </p>
            <button type="button" class="btn-modal-close" onclick="closeForgotModal()">Mengerti</button>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        }

        function fillDemo() {
            document.getElementById('login').value = 'admin_smk';
            document.getElementById('password').value = 'password';
        }

        function openForgotModal() {
            document.getElementById('forgot-modal').classList.add('show');
        }

        function closeForgotModal(e) {
            document.getElementById('forgot-modal').classList.remove('show');
        }
    </script>
</body>
</html>
