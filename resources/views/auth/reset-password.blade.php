@extends('layouts.auth')

@section('title', 'Reset Password')

@section('login')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.card-header h4').innerText = 'Reset Password';
        });
    </script>

    <form action="{{ route('password.update') }}" method="POST" role="form" class="text-start">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <div class="input-group input-group-outline is-filled @error('email') is-invalid @enderror">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="{{ $email ?? old('email') }}" required readonly>
            </div>
            @error('email')
                <div class="text-danger text-xs ps-1 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <div class="input-group input-group-outline position-relative @error('password') is-invalid @enderror">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control" style="padding-right: 40px;"
                    value="{{ $password ?? old('password') }}" required>
                <span id="togglePassword"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 100;">
                    <span class="material-symbols-rounded" style="font-size: 20px; vertical-align: middle;">
                        visibility_off
                    </span>
                </span>
            </div>
            @error('password')
                <div class="text-danger text-xs ps-1 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <div
                class="input-group input-group-outline position-relative @error('password_confirmation') is-invalid @enderror">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    style="padding-right: 40px;" required>
                <span id="togglePasswordConfirmation"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 100;">
                    <span class="material-symbols-rounded" style="font-size: 20px; vertical-align: middle;">
                        visibility_off
                    </span>
                </span>
            </div>
            @error('password_confirmation')
                <div class="text-danger text-xs ps-1 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">
                Reset Password
            </button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupToggle(toggleId, passwordId) {
                const toggle = document.getElementById(toggleId);
                const password = document.getElementById(passwordId);
                const icon = toggle ? toggle.querySelector('span.material-symbols-rounded') : null;

                if (toggle && password && icon) {
                    toggle.addEventListener('click', function(e) {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        icon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
                    });
                }
            }
            setupToggle('togglePasswordConfirmation', 'password_confirmation');
        });
    </script>
@endsection
