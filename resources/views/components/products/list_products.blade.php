@extends('main.main')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800">My Products</h1>
        <a href="{{ route('create-product') }}"
           class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow transition-all duration-200">
            + Add New Product
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden flex flex-col">

            <div class="relative h-48 bg-gray-200">
                <img src="https://images.app.goo.gl/v1uCovFMWKsniz3h6" alt="Product Image" class="absolute inset-0 w-full h-full object-cover">
            </div>

            <div class="p-4 flex flex-col gap-2 flex-grow">
                <div class="flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-800">Product 1</h5>
                    <span class="text-gray-500 font-medium">Rp15.000</span>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full border border-blue-400">Koleksi 1</span>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full border border-blue-400">Tipe 1</span>
                </div>
            </div>

            <div class="p-4 pt-0 flex flex-col gap-2">
                <form action="{{ route('detail-product') }}" method="GET">
                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition focus:ring-2 ring-blue-500">
                        See Detail
                    </button>
                </form>

                <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus koleksi ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded transition focus:ring-2 ring-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection