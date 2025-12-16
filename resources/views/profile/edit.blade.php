@extends('layouts.userapp')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Edit Profil</h1>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 1. Form Edit Data Diri (Tetap Ada) --}}
    <div class="card mb-4">
        <div class="card-header"><h5>Perbarui Informasi Profil</h5></div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                
                {{-- Input Name, Email, Phone, PIN (Biarkan Kode Lama Anda Disini) --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="{{ old('name', $decryptedName ?? Auth::user()->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="{{ old('email', Auth::user()->email) }}" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" class="form-control" 
                           value="{{ old('phone', Auth::user()->phone) }}">
                </div>
                <div class="mb-3">
                    <label for="pin_confirm" class="form-label text-danger fw-bold">Konfirmasi PIN Anda</label>
                    <input type="password" name="pin" id="pin_confirm" class="form-control" required placeholder="******">
                    @error('pin') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Profil</button>
            </form>
        </div>
    </div>

    {{-- 2. [UBAH DISINI] Bagian Ganti Password (Hanya Tombol) --}}
    <div class="card mb-4 border-warning">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title text-warning mb-1">Ganti Password</h5>
                <p class="card-text text-muted mb-0">Perbarui password Anda secara berkala demi keamanan.</p>
            </div>
            <a href="{{ route('password.edit') }}" class="btn btn-warning text-dark fw-bold">
                <i class="fa fa-key me-2"></i>Ganti Password
            </a>
        </div>
    </div>

    {{-- 3. Bagian Hapus Akun (Tetap Ada) --}}
    <div class="card border-danger mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Hapus Akun</h5>
        </div>
        <div class="card-body">
            <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan.</p>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                Hapus Akun Saya
            </button>
            
            {{-- Modal Delete (Biarkan Kode Lama Anda Disini) --}}
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Masukkan <strong>PIN</strong> untuk konfirmasi.</p>
                                <input type="password" name="pin" class="form-control" required placeholder="******">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection