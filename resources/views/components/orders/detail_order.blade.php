@extends('main.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Title -->
    <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-10">
        Detail Order: <span class="text-blue-600">#orderId</span>
    </h1>

    <!-- Status Select -->
    <div class="mb-6">
        <label for="type_id" class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
        <select id="type_id" name="type_id" required
            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <!-- option diisi secara dinamis -->
        </select>
    </div>

    <!-- User Info -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">User Email</h3>
            <p class="text-gray-600">user@email.com</p>

            <h3 class="text-lg font-semibold text-gray-800 mt-4">Shipping</h3>
            <p class="text-gray-600">shipping1</p>
        </div>

        <!-- Billing Address -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">User Address</h3>
                <div class="mt-2 space-y-1 text-gray-600">
                    <p><strong>First Name:</strong> Budi</p>
                    <p><strong>Last Name:</strong> Santoso</p>
                    <p><strong>Address:</strong> Jl. Anggrek No. 21</p>
                    <p><strong>Suite:</strong> Lantai 2</p>
                    <p><strong>City:</strong> Semarang</p>
                    <p><strong>Province:</strong> Jawa Tengah</p>
                    <p><strong>Postal Code:</strong> 50123</p>
                    <p><strong>Country:</strong> Indonesia</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Billing Address</h3>
                <div class="mt-2 space-y-1 text-gray-600">
                    <p><strong>First Name:</strong> Budi</p>
                    <p><strong>Last Name:</strong> Santoso</p>
                    <p><strong>Address:</strong> Jl. Anggrek No. 21</p>
                    <p><strong>Suite:</strong> Lantai 2</p>
                    <p><strong>City:</strong> Semarang</p>
                    <p><strong>Province:</strong> Jawa Tengah</p>
                    <p><strong>Postal Code:</strong> 50123</p>
                    <p><strong>Country:</strong> Indonesia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Method -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Shipping Method</h3>
        <p class="text-gray-600"><strong>Name:</strong> Yogyakarta</p>
        <p class="text-gray-600"><strong>Price:</strong> Rp20.000</p>
    </div>

    <!-- Total + Status + Date -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Total Price</h3>
            <p class="text-xl font-bold text-green-600">Rp60.000</p>
        </div>

        <div>
            <span
                class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-300">
                Status 1
            </span>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800">Order Date</h3>
            <p class="text-gray-600">09-07-2025</p>
        </div>
    </div>

    <!-- Order Items -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Items</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Item Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 flex flex-col overflow-hidden">
            <div class="relative h-48 bg-gray-100">
                <img src="https://images.app.goo.gl/v1uCovFMWKsniz3h6" alt="Product Image"
                    class="absolute inset-0 w-full h-full object-cover">
            </div>

            <div class="p-4 flex-grow">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Product 1</h3>
                <p class="text-sm text-gray-500">Quantity: 2</p>
                <p class="text-sm text-gray-500">Total Price: Rp30.000</p>
            </div>
        </div>

        <!-- Tambahkan lebih banyak card produk di sini -->
    </div>
</div>
@endsection