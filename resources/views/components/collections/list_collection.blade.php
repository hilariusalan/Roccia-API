@extends('main.main')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4 md:mb-0">My Collections</h1>
        <a href="{{ route('create-collection') }}"
            class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow transition-all duration-200 text-center">
            + Add New Collection
        </a>
    </div>

    {{-- Collection List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($collections as $collection)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden flex flex-col">
                <div class="relative h-48 bg-gray-200">
                    <img src="{{ $collection->image_url ?? 'https://via.placeholder.com/300' }}" alt="Collection Image"
                         class="absolute inset-0 w-full h-full object-cover">
                </div>

                <div class="p-4 flex flex-col gap-2 flex-grow">
                    <a href="{{ route('collections.products', ['collectionId' => $collection->id]) }}" class="text-lg font-semibold text-gray-800 hover:underline">{{ $collection->name }}</a>
                </div>

                <div class="p-4 pt-0 flex flex-col gap-2">
                    <form action="{{ route('collections.delete', $collection->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus koleksi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded transition focus:ring-2 ring-red-500">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-full">Tidak ada koleksi ditemukan.</p>
        @endforelse
    </div>
</div>
@endsection