@extends('layouts.userapp')

@section('title', 'Konfirmasi Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3>Konfirmasi Booking</h3>
                </div>
                <div class="card-body">
                    <h5>Detail Booking:</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Nama Pemilik:</strong> {{ Auth::user()->name }}</li>
                        <li class="list-group-item"><strong>Motor:</strong> 
                            {{ $booking->motor->jenisMotor->merek }} {{ $booking->motor->jenisMotor->tipe }} 
                            ({{ $booking->motor->plat }})
                        </li>
                        <li class="list-group-item"><strong>Slot Waktu:</strong> 
                            {{ $booking->timeSlot->start_time }} - {{ $booking->timeSlot->end_time }}
                        </li>
                        <li class="list-group-item"><strong>Mekanik:</strong> {{ $booking->timeSlot->mechanic->name }}</li>
                        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($booking->status) }}</li>
                    </ul>
                    
                    <h5>Layanan yang Dipilih:</h5>
                    <ul class="list-group">
                        @foreach ($booking->services as $service)
                            <li class="list-group-item">
                                {{ $service->serviceSpecification->name }} - Rp{{ number_format($service->serviceSpecification->price, 0, ',', '.') }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        <p class="text-muted">Silakan datang 30 menit sebelum waktu booking Anda.</p>
                        <a href="{{ route('user.services') }}" class="btn btn-secondary">Kembali ke Layanan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
