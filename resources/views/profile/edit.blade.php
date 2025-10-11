@extends('layouts.userapp')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Edit Profil</h1>

    <!-- Alert untuk pesan sukses -->
    @if (session('status') === 'profile-updated')
    <div class="alert alert-success">Profil berhasil diperbarui.</div>
    @elseif (session('status') === 'password-updated')
    <div class="alert alert-success">Kata sandi berhasil diperbarui.</div>
    @elseif (session('status') === 'user-deleted')
    <div class="alert alert-success">Akun berhasil dihapus.</div>
    @endif

    <!-- Form Perbarui Informasi Profil -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Perbarui Informasi Profil</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email

                                            class=" form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone', Auth::user()->phone) }}">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- Form Hapus Akun -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Hapus Akun</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.destroy') }}"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini? Data Anda akan hilang secara permanen.');">
                @csrf
                @method('DELETE')

                <p class="text-danger">Setelah akun Anda dihapus, semua data Anda akan dihapus secara permanen. Pastikan Anda telah menyimpan data penting sebelum melanjutkan.</p>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan kata sandi Anda untuk konfirmasi" required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger">Hapus Akun</button>
            </form>
        </div>
    </div>
</div>
@endsection