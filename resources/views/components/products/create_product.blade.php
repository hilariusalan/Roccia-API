@extends('main.main')

@section('content')
<div class="p-6 bg-white shadow-md rounded-2xl mt-10">
    <h1 class="text-center text-3xl font-bold text-gray-800 mb-8">Create New Product</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

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

    <p id="result" class="mt-6 text-sm text-gray-600"></p>
</div>
<script>
    
document.addEventListener('DOMContentLoaded', function () {
    // Populate collections and types
    fetch('/api/collections')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('collection_id');
            data.data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.text = item.name;
                select.appendChild(option);
            });
        });

    fetch('/api/types')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('type_id');
            data.data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.text = item.name;
                select.appendChild(option);
            });
        });

    // Image Upload
    const imageInput = document.getElementById('image_upload');
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        const formData = new FormData();
        formData.append('image', file);

        fetch('/upload-image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.url) {
                document.getElementById('image_url').value = data.url;
                document.getElementById('preview-image').innerHTML = `
                    <img src="${data.url}" alt="Preview" class="w-40 h-40 object-cover mt-2 rounded-lg shadow" />
                `;
            } else {
                alert('Upload gagal!');
            }
        })
        .catch(() => alert('Gagal mengunggah gambar'));
    });

    // Form Submit
    const form = document.getElementById('product-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('/products/create', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                document.getElementById('result').innerText = data.message;
                form.reset();
                document.getElementById('preview-image').innerHTML = '';
            } else {
                document.getElementById('result').innerText = data.error || 'Failed to create product.';
            }
        })
        .catch(err => {
            document.getElementById('result').innerText = 'Something went wrong.';
        });
    });
});
</script>
@endsection