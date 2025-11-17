@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('login')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.card-header h4').innerText = 'Lupa Password';
        });
    </script>

    <form action="{{ route('password.email') }}" method="POST" role="form" class="text-start">
        @csrf

        @if (session('status'))
            <div class="alert alert-success text-white text-center" role="alert" style="font-size: 0.875rem; padding: 0.75rem 1.25rem;">
                {{ session('status') }}
            </div>
        @else
            <div class="mb-3 text-sm text-center">
                Masukkan email Anda yang terdaftar. Kami akan mengirimkan link untuk me-reset password Anda.
            </div>
        @endif

        <div class="mb-3">
            <div
                class="input-group input-group-outline @if (old('email')) is-filled @endif @error('email') is-invalid @enderror">
                <label for="email" class="form-label">Masukkan Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                    required autofocus>
            </div>
            @error('email')
                <div class="text-danger text-xs ps-1 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">
                Kirim Link Reset Password
            </button>
        </div>

        <p class="mt-4 text-sm text-center">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-weight-bold">
                Login di sini
            </a>
        </p>
    </form>
@endsection
