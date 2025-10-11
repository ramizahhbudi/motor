@extends('layouts.userapp')

@section('title', 'Tambah Motor')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Tambah Motor</h1>

    <form action="{{ route('motor.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="jenis_motor_id" class="form-label">Jenis Motor</label>
            <select name="jenis_motor_id" id="jenis_motor_id" class="form-control" required>
                <option value="">Pilih Jenis Motor</option>
                @foreach ($jenisMotors as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->merek }} - {{ $jenis->tipe }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nomor_rangka" class="form-label">Nomor Rangka</label>
            <input type="text" name="nomor_rangka" id="nomor_rangka" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nomor_mesin" class="form-label">Nomor Mesin</label>
            <input type="text" name="nomor_mesin" id="nomor_mesin" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="plat" class="form-label">Plat Nomor</label>
            <input type="text" name="plat" id="plat" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Motor</button>
    </form>
</div>
@endsection
