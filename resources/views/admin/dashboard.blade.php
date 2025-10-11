@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Dashboard Admin</h1>
    <div class="mt-5">
        <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
        <p>Gunakan menu di atas untuk mengelola aplikasi.</p>
    </div>
    <div class="row g-4">
        <!-- Total Bookings Today -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h4 class="card-title">Booking Hari Ini</h4>
                    <p class="card-text display-4">{{ $totalBookingsToday }}</p>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-primary">Lihat Booking</a>
                </div>
            </div>
        </div>

        <!-- Total Payments Today -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h4 class="card-title">Pembayaran Hari Ini</h4>
                    <p class="card-text display-4">Rp{{ number_format($totalPaymentsToday, 2, ',', '.') }}</p>
                    <a href="{{ route('admin.cashier') }}" class="btn btn-primary">Kasir</a>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h4 class="card-title">Stok Rendah</h4>
                    <p class="card-text display-4">{{ $lowStockItems }}</p>
                    <a href="{{ route('admin.inventory') }}" class="btn btn-primary">Kelola Stok</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
