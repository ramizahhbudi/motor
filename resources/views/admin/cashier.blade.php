@extends('layouts.admin')

@section('title', 'Halaman Kasir')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Halaman Kasir</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.cashier.process') }}">
        @csrf
        <div class="mb-3">
            <label for="booking_id" class="form-label">Kode Booking</label>
            <input type="text" name="booking_id" id="booking_id" class="form-control" placeholder="Masukkan Kode Booking" required>
        </div>
        <button type="submit" class="btn btn-primary">Proses</button>
    </form>
</div>
@endsection
