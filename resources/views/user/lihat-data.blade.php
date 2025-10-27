@extends('layouts.userapp') {{-- Menggunakan layout userapp Anda --}}

@section('title', 'Lihat Data Saya')

@section('content')
<div class="container py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Form Input PIN --}}
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Input PIN Keamanan Anda</h4>
                </div>
                <div class="card-body p-4">
                    <p class="card-text text-muted">Untuk mengakses detail data, silakan masukkan 6 digit PIN yang Anda buat saat registrasi.</p>
                    
                    {{-- Form action menunjuk ke rute yang kita buat --}}
                    <form method="post" action="{{ route('user.view_data.decrypt') }}" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="pin_check" class="form-label fs-5"><strong>PIN</strong></label>
                            <input id="pin_check" name="pin" type="password" class="form-control form-control-lg @error('pin') is-invalid @enderror" maxlength="6" required placeholder="******" autofocus>
                            @error('pin')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Tampilkan Data</button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Bagian Hasil (Hanya tampil jika session 'view_data' ada) --}}
            @if (session('view_data'))
            @php $data = session('view_data'); @endphp
            <div class="card shadow-lg border-0">
                 <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0">Detail Data dan Proses Enkripsi</h4>
                 </div>
                <div class="card-body p-4" style="word-wrap: break-word;">
                    
                    <h5 class="text-dark border-bottom pb-2 mb-3">Data Asli</h5>
                    <div class="alert alert-info p-3">
                        <div class="row"><div class="col-sm-3"><strong>Username</strong></div><div class="col-sm-9">: {{ $data['original']['name'] }}</div></div><hr class="my-2">
                        <div class="row"><div class="col-sm-3"><strong>Password</strong></div><div class="col-sm-9">: {{ $data['original']['password'] }}</div></div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mt-4 mb-3">Hasil Enkripsi Playfair (Lapis 1)</h5>
                    <div class="alert alert-light border p-3">
                         <div class="row"><div class="col-sm-3"><strong>Username</strong></div><div class="col-sm-9">: {{ $data['playfair']['name'] }}</div></div><hr class="my-2">
                        <div class="row"><div class="col-sm-3"><strong>Password</strong></div><div class="col-sm-9">: {{ $data['playfair']['password'] }}</div></div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mt-4 mb-3">Hasil Enkripsi Caesar (Lapis 2)</h5>
                    <div class="alert alert-light border p-3">
                        <div class="row"><div class="col-sm-3"><strong>Username</strong></div><div class="col-sm-9">: {{ $data['caesar']['name'] }}</div></div><hr class="my-2">
                        <div class="row"><div class="col-sm-3"><strong>Password</strong></div><div class="col-sm-9">: {{ $data['caesar']['password'] }}</div></div>
                    </div>

                    <h5 class="text-dark border-bottom pb-2 mt-4 mb-3">Hasil Enkripsi Vigenere (Data Final di DB)</h5>
                    <div class="alert alert-light border p-3">
                        <div class="row"><div class="col-sm-3"><strong>Username</strong></div><div class="col-sm-9">: {{ $data['vigenere']['name'] }}</div></div><hr class="my-2">
                        <div class="row"><div class="col-sm-3"><strong>Password</strong></div><div class="col-sm-9">: {{ $data['vigenere']['password'] }}</div></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection