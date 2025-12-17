@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">Login</h1>

    {{-- BAGIAN 1: AREA ERROR DIBERI ID AGAR BISA DIUPDATE JS --}}
    @if ($errors->any())
        <div class="alert alert-danger" id="login-errors">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="pin" class="form-label">PIN</label>
            <input type="password" name="pin" id="pin" class="form-control" required maxlength="6">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <div class="text-center mt-3">
            <a href="{{ route('register') }}">Belum punya akun?</a>
        </div>
    </form>
</div>

{{-- SCRIPT PENGHITUNG MUNDUR (REALTIME) --}}
@if(session('lockout_time'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let seconds = parseInt("{{ session('lockout_time') }}");
        
        const errorDiv = document.getElementById('login-errors');
        
        if (errorDiv) {
            // Fungsi update teks
            const updateMessage = (sec) => {
                errorDiv.innerHTML = `
                    <strong>AKSES DIBLOKIR SEMENTARA!</strong><br>
                    Terlalu banyak percobaan gagal.<br>
                    Silakan tunggu <strong>${sec}</strong> detik lagi.
                `;
            };

            // Update pertama kali
            updateMessage(seconds);

            // Mulai hitung mundur
            const countdown = setInterval(() => {
                seconds--;
                
                if (seconds <= 0) {
                    // Jika waktu habis
                    clearInterval(countdown);
                    errorDiv.classList.remove('alert-danger');
                    errorDiv.classList.add('alert-success');
                    errorDiv.innerHTML = `
                        <strong>Waktu blokir habis.</strong><br>
                        Silakan <a href="javascript:window.location.reload()" class="alert-link">Refresh Halaman</a> untuk login kembali.
                    `;
                } else {
                    // Update detik
                    updateMessage(seconds);
                }
            }, 1000); // Update setiap 1000ms (1 detik)
        }
    });
</script>
@endif
@endsection