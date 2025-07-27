@extends('main.main')

@section('content')
<div class="p-6 bg-white shadow-md rounded-2xl mt-10">
    <h1 class="text-center text-3xl font-bold text-gray-800 mb-8">Create New Product</h1>
    <form id="product-form" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
            <input type="text" id="name" name="name" required class="w-full border rounded-lg p-2" />
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
            <input type="text" id="slug" name="slug" required class="w-full border rounded-lg p-2" />
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
            <input type="number" id="price" name="price" required class="w-full border rounded-lg p-2" />
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description" required class="w-full border rounded-lg p-2"></textarea>
        </div>

        <div>
            <label for="collection_id" class="block text-sm font-medium text-gray-700 mb-1">Collection</label>
            <select id="collection_id" name="collection_id" required class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select id="type_id" name="type_id" required class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="image_upload" class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
            <input type="file" id="image_upload" accept="image/*" class="w-full border rounded-lg p-2" />
            <input type="hidden" name="image_url" id="image_url">
            <div id="preview-image" class="mt-2"></div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
                Create Product
            </button>
        </div>
    </form>

    <div id="result" class="mt-6 text-sm text-gray-600"></div>
</div>
@endsection