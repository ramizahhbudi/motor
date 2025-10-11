@extends('layouts.userapp')

@section('title', 'Motor Pengguna')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Motor Anda</h1>

    @if ($motors->isEmpty())
        <!-- Jika Tidak Ada Motor -->
        <div class="alert alert-info text-center">
            <p class="mb-3">Anda belum memiliki motor yang terdaftar. Silakan tambahkan motor Anda untuk dapat menggunakan layanan servis.</p>
            <a href="{{ route('motor.create') }}" class="btn btn-primary">Tambah Motor</a>
        </div>
    @else
        <!-- Jika Ada Motor -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Motor Anda</h4>
            <a href="{{ route('motor.create') }}" class="btn btn-primary">Tambah Motor</a>
        </div>

        <div class="row">
            @foreach ($motors as $motor)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        @if ($motor->jenisMotor->gambar)
                        <img src="{{ asset($motor->jenisMotor->gambar) }}" class="card-img-top" alt="{{ $motor->jenisMotor->merek }} {{ $motor->jenisMotor->tipe }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/default-motor.jpg') }}" class="card-img-top" alt="Default Motor" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $motor->jenisMotor->merek }} {{ $motor->jenisMotor->tipe }}</h5>
                            <p class="card-text">
                                <strong>Nomor Rangka:</strong> {{ $motor->nomor_rangka }}<br>
                                <strong>Nomor Mesin:</strong> {{ $motor->nomor_mesin }}<br>
                                <strong>Plat:</strong> {{ $motor->plat }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('motor.destroy', $motor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus motor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                <a href="{{ route('motor.edit', $motor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
