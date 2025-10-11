@extends('layouts.admin')

@section('title', 'Daftar Booking')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Daftar Booking</h1>

    <!-- Filter and Search Form -->
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.bookings') }}">
            <div class="row g-2">
                <!-- Date Filter -->
                <div class="col-auto">
                    <label for="filter" class="form-label">Filter Waktu:</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="today" {{ $filterDate == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="yesterday" {{ $filterDate == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                        <option value="last7days" {{ $filterDate == 'last7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="last30days" {{ $filterDate == 'last30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="all" {{ $filterDate == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="col-auto">
                    <label for="search" class="form-label">Cari:</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           value="{{ $search }}" placeholder="Cari Booking ID, User, Motor">
                </div>

                <!-- Sort Order -->
                <div class="col-auto">
                    <label for="sort" class="form-label">Urutkan:</label>
                    <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Tanggal: Lama → Baru</option>
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Tanggal: Baru → Lama</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Booking Table -->
    @if ($bookings->isEmpty())
        <div class="alert alert-warning">Tidak ada booking untuk periode ini.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Nama User</th>
                    <th>Motor</th>
                    <th>Slot Waktu</th>
                    <th>Mekanik</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Dibuat Pada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $booking->booking_id }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>
                            {{ $booking->motor->jenisMotor->merek }} 
                            {{ $booking->motor->jenisMotor->tipe }} 
                            ({{ $booking->motor->plat }})
                        </td>
                        <td>{{ $booking->timeSlot->start_time }} - {{ $booking->timeSlot->end_time }}</td>
                        <td>{{ $booking->timeSlot->mechanic->name }}</td>
                        <td>
                            @foreach ($booking->services as $service)
                                <div>{{ $service->serviceSpecification->name }}</div>
                            @endforeach
                        </td>
                        <td>{{ ucfirst($booking->status) }}</td>
                        <td>{{ $booking->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
