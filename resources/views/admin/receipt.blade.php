@extends('layouts.admin')

@section('title', 'Struk Pembayaran')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-body">
            <h2 class="text-center">Struk Pembayaran</h2>
            <hr>
            <p><strong>Kode Booking:</strong> {{ $payment->booking->booking_id }}</p>
            <p><strong>Nama Pelanggan:</strong> {{ $payment->booking->user->name }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Tanggal:</strong> {{ $payment->created_at->format('d-m-Y H:i') }}</p>

            <h4>Layanan</h4>
            <ul>
                @foreach ($payment->booking->services as $service)
                    <li>{{ $service->serviceSpecification->name }} - Rp{{ number_format($service->serviceSpecification->price, 2, ',', '.') }}</li>
                @endforeach
            </ul>
            <hr>
            <h3>Total Bayar: Rp{{ number_format($payment->amount, 2, ',', '.') }}</h3>

            <hr>
            <div class="text-center">
                <a href="{{ route('admin.cashier') }}" class="btn btn-secondary">Kembali ke Kasir</a>
            </div>
        </div>
    </div>
</div>
@endsection
