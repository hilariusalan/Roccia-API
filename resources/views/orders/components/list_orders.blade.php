@extends('orders.order')

@section('content')
<div class="container">
    <h1>Daftar Pesanan</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ url('/orders') }}" class="mb-4">
        <div class="row">
            <!-- Date Filter -->
            <div class="col-md-4">
                <label for="date">Tanggal</label>
                <input type="date" id="date" name="date" value="{{ $filters['date'] }}" class="form-control">
            </div>

            <!-- Status Filter -->
            <div class="col-md-4">
                <label for="status_id">Status Pesanan</label>
                <select id="status_id" name="status_id" class="form-control">
                    <option value="">-- Semua Status --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ $filters['status_id'] == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
            </div>
        </div>
    </form>

    <!-- Order Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->users->name ?? '-' }}</td>
                <td>{{ $order->statuses->name ?? '-' }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Tidak ada pesanan ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
