@extends('layouts.userapp')

@section('title', 'Lihat Data Saya')

@section('content')

{{-- TAMBAHKAN KODE PHP INI UNTUK AMBIL DATA DARI CONTROLLER --}}
@php
    $data = session('view_data');
@endphp

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="text-primary text-uppercase">Data Transparansi Pengguna</h1>
        <p class="text-muted">Masukkan PIN untuk melihat proses enkripsi data Anda secara real-time.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-light text-white">
                    <h5 class="mb-0"><i class="fa fa-lock me-2"></i>Verifikasi Keamanan</h5>
                </div>
                <div class="card-body">
                    
                    {{-- UBAH KONDISI IF DISINI --}}
                    @if (!$data) 
                    
                        {{-- Form Input PIN (Tetap sama seperti kode Anda) --}}
                        <form action="{{ url('/lihat-data') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="pin" class="fw-bold">Masukkan PIN Anda:</label>
                                <input type="password" name="pin" class="form-control form-control-lg text-center" 
                                       placeholder="******" maxlength="6" required>
                                @error('pin')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3">Buka Enkripsi Data</button>
                        </form>
                    
                    @else
                        {{-- Bagian Tabel (Tetap sama, karena Controller sudah menyesuaikan struktur datanya) --}}
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> Data berhasil didekripsi menggunakan PIN Anda.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Jenis Data</th>
                                        <th>Data Asli (Plaintext)</th>
                                        <th>Tahap 1 (Playfair)</th>
                                        <th>Tahap 2 (Caesar)</th>
                                        <th>Tahap 3 (Vigenere)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Baris NAMA --}}
                                    <tr>
                                        <td class="fw-bold">Nama Lengkap</td>
                                        {{-- Controller sudah mengirim array ['name']['original'] --}}
                                        <td class="text-primary fw-bold">{{ $data['name']['original'] }}</td>
                                        <td class="text-muted small">{{ $data['name']['playfair'] }}</td>
                                        <td class="text-muted small">{{ $data['name']['caesar'] }}</td>
                                        <td class="text-danger fw-bold">{{ $data['name']['vigenere'] }}</td>
                                    </tr>

                                    {{-- Baris PASSWORD --}}
                                    <tr>
                                        <td class="fw-bold">Password</td>
                                        {{-- Di Controller ini sudah diset jadi '********' --}}
                                        <td class="text-primary fw-bold">{{ $data['password']['original'] }}</td>
                                        <td class="text-muted small">{{ $data['password']['playfair'] }}</td>
                                        <td class="text-muted small">{{ $data['password']['caesar'] }}</td>
                                        <td class="text-danger fw-bold">{{ $data['password']['vigenere'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Info Tambahan AES --}}
                        <div class="mt-4 p-3 bg-light rounded border">
                            <h6 class="fw-bold">Data Email & Telepon (AES-256)</h6>
                            {{-- Pastikan key array sesuai dengan controller --}}
                            <p class="mb-1">Email: <strong>{{ $data['email'] }}</strong> <span class="badge bg-success">Verified AES</span></p>
                            <p class="mb-0">Telepon: <strong>{{ $data['phone'] }}</strong> <span class="badge bg-success">Verified AES</span></p>
                        </div>

                        <div class="mt-3 text-center">
                            <a href="{{ route('user_home')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection