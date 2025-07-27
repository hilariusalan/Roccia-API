@extends('orders.order')

@section('content')
<div class="container my-4">
    <h2 class="mb-4">Detail Order</h2>

    <div class="card mb-4">
        <div class="card-header">Informasi Pemesan</div>
        <div class="card-body">
            <p><strong>Email Pengguna:</strong> <span id="userEmail">Memuat...</span></p>
            <p><strong>Alamat Pengiriman:</strong> <span id="shippingName">Memuat...</span></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Alamat Penagihan</div>
        <div class="card-body" id="billingAddress">
            <p>Memuat...</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Metode Pengiriman</div>
        <div class="card-body">
            <p><strong>Metode:</strong> <span id="shippingMethodName">Memuat...</span></p>
            <p><strong>Harga:</strong> Rp <span id="shippingMethodPrice">0</span></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Item dalam Order</div>
        <div class="card-body">
            <table class="table table-bordered" id="itemsTable">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Status dan Total</div>
        <div class="card-body">
            <form id="statusForm">
                <div class="mb-3">
                    <label for="status" class="form-label">Status Pesanan</label>
                    <select class="form-select" id="status" name="status_id">
                        <option value="">Memuat status...</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui Status</button>
            </form>

            <hr>
            <p><strong>Total Harga:</strong> Rp <span id="totalPrice">0</span></p>
            <p><strong>Dibuat:</strong> <span id="createdAt"></span></p>
            <p><strong>Terakhir Diperbarui:</strong> <span id="updatedAt"></span></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const orderId = "{{ $orderId }}";
        const urlDetail = `/api/orders/${orderId}`;
        const urlStatuses = `/api/statuses`;

        // Fetch detail order
        fetch(urlDetail)
            .then(response => response.json())
            .then(res => {
                const data = res.data;
                document.getElementById('userEmail').innerText = data.user;
                document.getElementById('shippingName').innerText = data.shipping;

                // Billing address
                const ba = data.billing_address;
                document.getElementById('billingAddress').innerHTML = `
                    <p>${ba.first_name} ${ba.last_name}</p>
                    <p>${ba.address}, ${ba.appartment_suite}</p>
                    <p>${ba.city}, ${ba.province}, ${ba.country}</p>
                `;

                // Shipping method
                document.getElementById('shippingMethodName').innerText = data.shipping_method.name;
                document.getElementById('shippingMethodPrice').innerText = data.shipping_method.price;

                // Total & Dates
                document.getElementById('totalPrice').innerText = data.total_price;
                document.getElementById('createdAt').innerText = data.created_at;
                document.getElementById('updatedAt').innerText = data.updated_at;

                // Items
                const tbody = document.querySelector('#itemsTable tbody');
                tbody.innerHTML = '';
                data.items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.product_name}</td>
                        <td>${item.variant}</td>
                        <td>${item.quantity}</td>
                        <td>Rp ${item.price}</td>
                    `;
                    tbody.appendChild(tr);
                });

                // Pre-select current status
                fetch(urlStatuses)
                    .then(resp => resp.json())
                    .then(statusData => {
                        const select = document.getElementById('status');
                        select.innerHTML = '';
                        statusData.data.forEach((status, index) => {
                            const opt = document.createElement('option');
                            opt.value = status.id;
                            opt.text = status.name;
                            if (status.name === data.status) opt.selected = true;
                            select.appendChild(opt);
                        });
                    });
            });

        // Handle update status
        document.getElementById('statusForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const statusId = document.getElementById('status').value;

            fetch(`/api/orders/${orderId}/update-status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status_id: statusId })
            })
            .then(res => res.json())
            .then(response => {
                alert("Status berhasil diperbarui!");
                location.reload();
            })
            .catch(error => {
                console.error("Gagal memperbarui status:", error);
            });
        });
    });
</script>
@endsection
