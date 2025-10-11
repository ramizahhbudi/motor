@extends('layouts.userapp')

@section('title', 'User Home')

@section('content')

<!-- Layanan Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Registrasi Berhasil & PIN Dienkripsi!</h4>
            <p>PIN Anda telah dienkripsi dengan aman melalui 3 lapisan. Berikut adalah detail prosesnya:</p>
            <hr>
            <ul class="mb-0">
                <li><strong>PIN Asli Anda:</strong> {{ session('encryption_steps')['original_pin'] }}</li>
                <li><strong>1. Hasil Enkripsi Playfair:</strong> {{ session('encryption_steps')['step1_playfair'] }}</li>
                <li><strong>2. Hasil Enkripsi Caesar:</strong> {{ session('encryption_steps')['step2_caesar'] }}</li>
                <li><strong>3. Hasil Akhir (Vigenere) yang Disimpan:</strong> <strong>{{ session('encryption_steps')['step3_vigenere_final'] }}</strong></li>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>
                    <p class="card-text">
                        Anda telah berhasil login ke dalam sistem.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Layanan End -->

@endsection