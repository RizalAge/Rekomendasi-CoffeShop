@extends('layouts.app')

@section('content')
<style>
    .auth-wrapper {
        min-height: calc(100vh - 56px);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    .auth-card {
        width: 100%;
        max-width: 440px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: none;
    }
    .auth-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 1.2rem;
        font-weight: 600;
        text-align: center;
        border-radius: 16px 16px 0 0 !important;
        padding: 1.2rem;
        border: none;
    }
    .auth-card .card-body {
        padding: 2rem;
    }
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    #togglePassword, #toggleConfirmPassword {
        position: absolute;
        right: 12px;
        cursor: pointer;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
        z-index: 10;
    }
    #togglePassword:hover, #toggleConfirmPassword:hover { color: #333; }
    #password, #password-confirm { padding-right: 40px !important; }
    .btn-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border-radius: 8px;
        color: white;
        transition: opacity 0.2s, transform 0.2s;
    }
    .btn-auth:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
    }
</style>

<div class="auth-wrapper">
    <div class="px-3 w-100 d-flex justify-content-center">
        <div class="auth-card card">
            <div class="card-header">Register</div>
            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                        <input id="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}"
                               required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="password-wrapper">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password">
                            <span id="togglePassword">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-semibold">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input id="password-confirm" type="password"
                                   class="form-control"
                                   name="password_confirmation" required autocomplete="new-password">
                            <span id="toggleConfirmPassword">
                                <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-auth">Daftar Sekarang</button>

                    <p class="text-center mt-3 mb-0 text-muted small">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setupPasswordToggle(inputId, toggleId, iconId) {
        const toggle = document.querySelector('#' + toggleId);
        const input = document.querySelector('#' + inputId);
        const icon = document.querySelector('#' + iconId);

        toggle.addEventListener('click', function () {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            if (type === 'text') {
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/><line x1="1" y1="1" x2="23" y2="23"/>';
                icon.style.stroke = '#0d6efd';
            } else {
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                icon.style.stroke = 'currentColor';
            }
        });
    }

    setupPasswordToggle('password', 'togglePassword', 'eyeIcon');
    setupPasswordToggle('password-confirm', 'toggleConfirmPassword', 'eyeIconConfirm');
</script>
@endsection