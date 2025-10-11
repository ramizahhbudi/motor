@extends('layouts.admin')

@section('title', 'Manajemen Inventaris')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Manajemen Stock</h1>

    <!-- Search Form -->
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.inventory') }}">
            <div class="row g-2">
                <div class="col-auto">
                    <label for="search" class="form-label">Cari:</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           value="{{ $search }}" placeholder="Cari nama layanan atau spesifikasi">
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    @if ($inventory->isEmpty())
        <div class="alert alert-warning">Tidak ada data inventaris yang ditemukan.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Layanan</th>
                    <th>Nama Spesifikasi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventory as $index => $item)
                    <tr>
                        <td>{{ $index + $inventory->firstItem() }}</td>
                        <td>{{ $item->service->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>Rp{{ number_format($item->price, 2, ',', '.') }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>
                            <!-- Edit Stock Form -->
                            <form method="POST" action="{{ route('admin.inventory.update', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <input type="number" name="stock" value="{{ $item->stock }}" 
                                           class="form-control" min="0" required>
                                    <button type="submit" class="btn btn-success">Perbarui</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $inventory->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
