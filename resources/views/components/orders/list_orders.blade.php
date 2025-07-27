@extends('main.main')

@section('content')
<div class="container mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-10">My Orders</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden flex flex-col">
            <div class="p-6 flex-grow flex flex-col gap-3">
                <h2 class="text-xl font-bold text-gray-700">#1</h2>
                <p class="text-gray-600 font-medium">user@email.com</p>
                <p class="text-gray-400 text-sm">27-07-2025</p>

                <span class="bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full border border-red-400 w-fit">
                    Status 1
                </span>
            </div>

            <div class="p-4 pt-0">
                <form action="{{ route('detail-order') }}" method="GET">
                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition focus:ring-2 ring-blue-500">
                        See Detail
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection