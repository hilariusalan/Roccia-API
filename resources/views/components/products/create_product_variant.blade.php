@extends('main.main')

@section('content')
<div class="p-6 bg-white shadow-md rounded-2xl mt-10">
    <h1 class="text-center text-3xl font-bold text-gray-800 mb-8">Create New Variant</h1>
    <form id="variant-form" class="space-y-6">
        @csrf

        <div>
            <label for="image_upload" class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
            <input type="file" id="image_upload" accept="image/*" class="w-full border rounded-lg p-2" />
            <input type="hidden" name="image_url" id="image_url">
            <div id="preview-image" class="mt-2"></div>
        </div>

        <div>
            <label for="color_id" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <select id="color_id" name="color_id" required class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="size_id" class="block text-sm font-medium text-gray-700 mb-1">Size</label>
            <select id="size_id" name="size_id" required class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="fabric_id" class="block text-sm font-medium text-gray-700 mb-1">Fabric</label>
            <select id="fabric_id" name="fabric_id" required class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <input id="stock" name="stock" type="number" required class="w-full border rounded-lg p-2" />
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
                Create Variant
            </button>
        </div>
    </form>

    <div id="result" class="mt-6 text-sm text-gray-600"></div>
</div>
@endsection