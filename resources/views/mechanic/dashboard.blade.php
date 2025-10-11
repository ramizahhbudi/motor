@extends('layouts.mekanik')

@section('title', 'Dashboard Mekanik')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Jadwal Servis Hari Ini</h1>

    @if ($schedules->isEmpty())
        <div class="alert alert-warning">Tidak ada jadwal servis untuk hari ini.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Motor</th>
                    <th>Jenis Layanan</th>
                    <th>Slot Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $index => $schedule)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $schedule->booking_id }}</td>
                        <td>{{ $schedule->user->name }}</td>
                        <td>
                            {{ $schedule->motor->jenisMotor->merek }}
                            {{ $schedule->motor->jenisMotor->tipe }}
                            ({{ $schedule->motor->plat }})
                        </td>
                        <td>
                            @foreach ($schedule->services as $service)
                                <div>{{ $service->serviceSpecification->name }}</div>
                            @endforeach
                        </td>
                        <td>{{ $schedule->timeSlot->start_time }} - {{ $schedule->timeSlot->end_time }}</td>
                        <td>{{ ucfirst($schedule->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
