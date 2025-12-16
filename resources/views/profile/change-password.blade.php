@extends('layouts.userapp')

@section('title', 'Ganti Password')

@section('content')
<div class="container py-5">
    {{-- Header dengan Tombol Kembali --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Ganti Password</h1>
        <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-2"></i>Kembali ke Profil
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Form Perubahan Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.updatePassword') }}">
                        @csrf
                        @method('PATCH')

                        {{-- Password Lama --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" required>
                            @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        {{-- PIN (Wajib untuk Enkripsi 3-Lapis) --}}
                        <div class="mb-4">
                            <label for="pin_pass" class="form-label text-danger fw-bold">Konfirmasi PIN Anda</label>
                            <input type="password" name="pin" id="pin_pass" class="form-control" required placeholder="******">
                            <small class="text-muted">PIN dibutuhkan untuk mengenkripsi password baru Anda.</small>
                            @error('pin') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">Simpan Password Baru</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection