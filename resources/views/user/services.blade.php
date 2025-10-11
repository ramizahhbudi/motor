@extends('layouts.userapp')

@section('title', 'Layanan Kami')

@section('content')
@if(session('warning'))
<div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<!-- Pemesanan Dimulai -->
<div class="container-fluid bg-secondary booking my-5">
    <div class="container">
        <div class="row gx-5">
            <!-- Form Booking -->
            <div class="col-lg-6 py-5">
                <h1 class="text-white mb-4">Pesan Layanan</h1>
                @if ($userMotors->isEmpty())
                <div class="alert alert-warning">
                    Anda belum mendaftarkan motor. Silakan <a href="{{ route('motor.create') }}" class="text-primary">daftarkan motor</a> terlebih dahulu untuk memesan layanan.
                </div>
                @endif
                <form method="POST" action="{{ route('booking.store') }}" {{ $userMotors->isEmpty() ? 'class=disabled-form' : '' }}>
                    @csrf
                    <!-- Pilih Motor -->
                    <div class="mb-3">
                        <select class="form-select" name="motor_id" {{ $userMotors->isEmpty() ? 'disabled' : '' }} required>
                            <option selected disabled>Pilih Motor Anda</option>
                            @foreach ($userMotors as $motor)
                            <option value="{{ $motor->id }}">
                                {{ $motor->jenisMotor->merek }} - {{ $motor->jenisMotor->tipe }} - {{ $motor->plat }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Layanan -->
                    <div class="mb-3">
                        <select class="form-select" name="service_id" id="service-dropdown" {{ $userMotors->isEmpty() ? 'disabled' : '' }} required>
                            <option selected disabled>Pilih Layanan</option>
                            @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Spesifikasi Layanan -->
                    <div class="mb-3">
                        <select class="form-select" name="service_specification_id" id="specifications-dropdown" {{ $userMotors->isEmpty() ? 'disabled' : '' }} required>
                            <option selected disabled>Pilih Spesifikasi</option>
                        </select>
                    </div>

                    <!-- Slot Waktu -->
                    <div class="mb-3">
                        <select class="form-select" name="time_slot_id" {{ $userMotors->isEmpty() ? 'disabled' : '' }} required>
                            <option selected disabled>Pilih Slot Waktu</option>
                            @foreach ($availableSlots as $slot)
                            <option value="{{ $slot->id }}">
                                {{ $slot->start_time }} - {{ $slot->end_time }} ({{ $slot->mechanic->name }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <textarea class="form-control" name="notes" {{ $userMotors->isEmpty() ? 'disabled' : '' }} placeholder="Permintaan Khusus"></textarea>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary mt-3 w-100" {{ $userMotors->isEmpty() ? 'disabled' : '' }}>Pesan Sekarang</button>
                </form>
            </div>

            <!-- Daftar Pengajuan Booking -->
            <div class="col-lg-6 py-5">
                <h1 class="text-white mb-4">Daftar Pengajuan Booking</h1>
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Detail</th>
                                    <th>Info</th>
                                </tr>
                            </thead>
                            <tbody id="booking-list">
                                <!-- Data booking akan diisi secara dinamis di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch specifications dynamically
    document.getElementById('service-dropdown').addEventListener('change', function() {
        const serviceId = this.value;
        const specificationsDropdown = document.getElementById('specifications-dropdown');
        specificationsDropdown.innerHTML = '<option selected disabled>Pilih Spesifikasi</option>';

        fetch(`/services/${serviceId}/specifications`)
            .then(response => response.json())
            .then(data => {
                data.forEach(spec => {
                    const option = document.createElement('option');
                    option.value = spec.id;
                    option.textContent = `${spec.name} - Rp${spec.price.toLocaleString('id-ID')}`;
                    specificationsDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching specifications:', error));
    });

    // Fetch booking list
    fetch('/user/bookings')
        .then(response => response.json())
        .then(data => {
            const bookingList = document.getElementById('booking-list');
            bookingList.innerHTML = ''; // Kosongkan tabel sebelum diisi

            // Loop untuk setiap booking
            data.forEach(booking => {
                const services = booking.services.map(service => `${service.service_specification.name}`).join(', ');
                const row = `
                    <tr>
                        <td><strong>Motor</strong></td>
                        <td>${booking.motor.jenis_motor.merek} ${booking.motor.jenis_motor.tipe} (${booking.motor.plat})</td>
                    </tr>
                    <tr>
                        <td><strong>Slot Waktu</strong></td>
                        <td>${booking.time_slot.start_time} - ${booking.time_slot.end_time}</td>
                    </tr>
                    <tr>
                        <td><strong>Mekanik</strong></td>
                        <td>${booking.time_slot.mechanic.name}</td>
                    </tr>
                    <tr>
                        <td><strong>Layanan</strong></td>
                        <td>${services}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>${booking.status}</td>
                    </tr>
                    <tr>
                        <td><strong>Booking ID</strong></td>
                        <td>${booking.booking_id}</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                `;
                bookingList.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching bookings:', error));
</script>
@endsection