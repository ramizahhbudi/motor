@extends('layouts.userapp')

@section('title', 'User Home')

@section('content')

{{-- 1. KITA PANGGIL SERVICE ENKRIPSI DI SINI (Hanya untuk Tampilan) --}}
@inject('encryptionService', 'App\Services\EncryptionService')

{{-- 2. LOGIKA DEKRIPSI SEMENTARA --}}
@php
    // Ambil nama acak dari database
    $tampilanNama = Auth::user()->name; 
    
    // Ambil PIN sementara dari sesi login
    $pin = session('auth_pin');

    // Jika ada PIN, coba buka kuncinya hanya untuk variabel $tampilanNama
    if ($pin) {
        try {
            $tampilanNama = $encryptionService->decrypt(Auth::user()->name, $pin);
        } catch (\Exception $e) {
            // Jika error, biarkan tetap kode acak
        }
    }
@endphp

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            {{-- (Bagian ikon layanan biarkan sama...) --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="d-flex py-5 px-4">
                    <i class="fa fa-certificate fa-3x text-primary flex-shrink-0"></i>
                    <div class="ps-4">
                        <h5 class="mb-3">Layanan Berkualitas</h5>
                        <p>Kami memberikan pelayanan terbaik dengan standar kualitas tinggi.</p>
                        <a class="text-secondary border-bottom" href="">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="d-flex bg-light py-5 px-4">
                    <i class="fa fa-users-cog fa-3x text-primary flex-shrink-0"></i>
                    <div class="ps-4">
                        <h5 class="mb-3">Tenaga Ahli</h5>
                        <p>Tim kami terdiri dari teknisi berpengalaman dan profesional.</p>
                        <a class="text-secondary border-bottom" href="">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="d-flex py-5 px-4">
                    <i class="fa fa-tools fa-3x text-primary flex-shrink-0"></i>
                    <div class="ps-4">
                        <h5 class="mb-3">Peralatan Modern</h5>
                        <p>Kami menggunakan peralatan canggih untuk hasil terbaik.</p>
                        <a class="text-secondary border-bottom" href="">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    
    @if (session('encryption_steps'))
        {{-- (Bagian Alert Info Registrasi biarkan sama...) --}}
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Registrasi Berhasil & PIN Dienkripsi!</h4>
            <p>PIN Anda telah dienkripsi dengan aman melalui 3 lapisan...</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- 3. TAMPILKAN NAMA YANG SUDAH DIBUKA --}}
                    <h5 class="card-title">Selamat Datang, {{ $tampilanNama }}!</h5>
                    
                    <p class="card-text">
                        Anda telah berhasil login ke dalam sistem.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection