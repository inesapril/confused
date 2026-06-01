<x-guest-layout title="Login - Alur Confused" mode="login">
    <style>
        body {
            background: #292c31;
            font-family: 'Segoe UI', sans-serif;
            color: #1f1f1f;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 42px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e5e5e5;
        }

        .brand-title {
            font-size: clamp(24px, 3vw, 32px);
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.5px;
            text-align: center;
        }

        .brand-subtitle {
            font-size: clamp(13px, 2vw, 14px);
            color: #6b7280;
            text-align: center;
            margin-top: 6px;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.08);
            outline: none;
        }

        .btn-login {
            background-color: #111827;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            border: none;
            font-size: 15px;
            transition: 0.2s ease;
        }

        .btn-login:hover {
            background-color: #000;
            transform: translateY(-1px);
        }

        .forgot-link {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #111827;
        }

        /* Responsive tweak */
        @media (max-width: 480px) {
            .login-card {
                padding: 28px;
                border-radius: 14px;
            }

            .btn-login {
                font-size: 14px;
            }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="brand-title">
                Alur Confused
            </div>
            <div class="brand-subtitle">
                Login untuk melanjutkan ke sistem
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input
                        type="text"
                        name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}"
                        required
                        autofocus
                    >
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end mb-3">
                    <a href="#" class="forgot-link">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit" class="btn-login">
                    LOGIN
                </button>
            </form>

        </div>
    </div>
</x-guest-layout>