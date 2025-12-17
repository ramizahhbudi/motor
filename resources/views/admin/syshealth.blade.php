@extends('layouts.admin')

@section('content')
<div class="container py-4"> 
    <div class="row justify-content-center">
        <div class="col-lg-10"> 
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="m-0 font-weight-bold text-gray-800">Audit Integritas Sistem</h5>
                @if($audit['status'] === 'SECURE')
                    <span class="badge badge-pill shadow-sm px-3 py-2 border border-success text-success bg-white">
                        <i class="fas fa-shield-alt mr-1"></i> {{ $audit['status'] }}
                    </span>
                @else
                    <span class="badge badge-pill shadow-sm px-3 py-2 border border-danger text-danger bg-white">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $audit['status'] }}
                    </span>
                @endif
            </div>

            <div class="card border-0 shadow-sm mb-4 bg-white" style="border-left: 4px solid {{ $audit['status'] === 'SECURE' ? '#1cc88a' : '#e74a3b' }} !important;">
                <div class="card-body py-3 d-flex align-items-center">
                    <i class="fas {{ $audit['status'] === 'SECURE' ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} fa-lg mr-3"></i>
                    <span class="text-dark small font-weight-bold">{{ $audit['message'] }}</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 8px;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="bg-danger text-white"> 
                        <tr>
                            <th class="py-2 pl-3 border-0 small">BLOCK</th>
                            <th class="py-2 border-0 small">MODEL</th>
                            <th class="py-2 border-0 small text-center">HASH VALIDASI</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($ledgers as $ledger)
                        <tr class="{{ $audit['compromised_block'] == $ledger->id ? 'table-danger' : '' }}">
                            <td class="pl-3 align-middle small font-weight-bold">#{{ $ledger->id }}</td>
                            <td class="align-middle">
                                <span class="d-block text-dark small font-weight-bold">{{ class_basename($ledger->model_type) }}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">Ref ID: {{ $ledger->model_id }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <code class="text-xs text-primary bg-light px-2 py-1 rounded border">
                                    {{ substr($ledger->current_hash, 0, 12) }}...
                                </code>
                                @if($audit['status'] === 'COMPROMISED' && $ledger->id >= $audit['compromised_block'])
                                    <i class="fas fa-times-circle text-danger ml-1 small"></i>
                                @else
                                    <i class="fas fa-check-circle text-success ml-1 small"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $ledgers->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection