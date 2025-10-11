@extends('layouts.admin')

@section('title', 'Proses Pembayaran')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Proses Pembayaran</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Detail Booking</h4>
            <p><strong>Kode Booking:</strong> {{ $booking->booking_id }}</p>
            <p><strong>Nama Pelanggan:</strong> {{ $booking->user->name }}</p>
            <p><strong>Layanan:</strong></p>
            <ul>
                @foreach ($booking->services as $service)
                    <li>{{ $service->serviceSpecification->name }} - Rp{{ number_format($service->serviceSpecification->price, 2, ',', '.') }}</li>
                @endforeach
            </ul>
            <p><strong>Total Bayar:</strong> Rp{{ number_format($totalAmount, 2, ',', '.') }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.cashier.finalize', $booking->id) }}">
        @csrf
        <input type="hidden" name="amount" value="{{ $totalAmount }}">
        <div class="mb-3">
            <label for="payment_method" class="form-label">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="cash">Tunai</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Selesaikan Pembayaran</button>
    </form>
</div>
@endsection
