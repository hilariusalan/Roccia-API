@extends('main.main')

@section('content')
<script>
    function openEditStockPopup() {
        alert("Form edit stock akan muncul di sini (bisa diganti dengan modal)");
    }
</script>
<div class="container mx-auto px-6 py-10">
    <h1 class="text-center text-4xl font-bold mb-11 text-gray-800">Detail Product</h1>

    <!-- Section: Product Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start mb-10">
        <!-- Product Image -->
        <div class="w-full">
            <img src="https://images.app.goo.gl/v1uCovFMWKsniz3h6" alt="Product Image" class="rounded-xl w-full object-cover shadow-md">
        </div>

        <!-- Product Info -->
        <div class="flex flex-col gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Product Name</h2>

            <div class="flex gap-2 flex-wrap">
                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full border border-blue-400">
                    Koleksi 1
                </span>
                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full border border-blue-400">
                    Tipe 1
                </span>
            </div>

            <p class="text-gray-600 leading-relaxed">
                Deskripsi produk ini memberikan informasi lengkap mengenai manfaat, bahan, dan keunggulan lainnya dari produk yang ditampilkan.
            </p>
        </div>
    </div>

    <!-- Section: Product Variants -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Product Variants</h2>
        <a href="{{ route('create-variant') }}"
           class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md transition-all duration-200">
            + Create New Variant
        </a>
    </div>

    <!-- Variant Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 flex flex-col overflow-hidden">
            <div class="relative h-48 bg-gray-100">
                <img src="https://images.app.goo.gl/v1uCovFMWKsniz3h6" alt="Product Image" class="absolute inset-0 w-full h-full object-cover">
            </div>

            <div class="p-4 flex-grow">
                <h3 class="text-xl font-semibold text-gray-800 mb-1">Product 1</h3>
                <p class="text-sm text-gray-500">Stock: 20</p>
            </div>

            <div class="p-4 pt-0 flex flex-col gap-2">
                <!-- Edit Stock -->
                <form action="#" method="GET" onsubmit="openEditStockPopup(); return false;">
                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Edit Stock
                    </button>
                </form>

                <!-- Delete -->
                <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus koleksi ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection