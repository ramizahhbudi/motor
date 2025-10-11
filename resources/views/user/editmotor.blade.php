@extends('layouts.userapp')

@section('title', 'Edit Motor')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Edit Motor</h1>

    <form action="{{ route('motor.update', $motor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="jenis_motor_id" class="form-label">Jenis Motor</label>
            <select name="jenis_motor_id" id="jenis_motor_id" class="form-control" required>
                <option value="">Pilih Jenis Motor</option>
                @foreach ($jenisMotors as $jenis)
                    <option value="{{ $jenis->id }}" {{ $motor->jenis_motor_id == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->merek }} - {{ $jenis->tipe }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nomor_rangka" class="form-label">Nomor Rangka</label>
            <input type="text" name="nomor_rangka" id="nomor_rangka" class="form-control" value="{{ $motor->nomor_rangka }}" required>
        </div>

        <div class="mb-3">
            <label for="nomor_mesin" class="form-label">Nomor Mesin</label>
            <input type="text" name="nomor_mesin" id="nomor_mesin" class="form-control" value="{{ $motor->nomor_mesin }}" required>
        </div>

        <div class="mb-3">
            <label for="plat" class="form-label">Plat Nomor</label>
            <input type="text" name="plat" id="plat" class="form-control" value="{{ $motor->plat }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('motor.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
