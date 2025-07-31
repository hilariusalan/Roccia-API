@extends('main.main')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Create New Collection</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form id="collection-form" action="{{ route('collections.create') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Collection Name</label>
            <input type="text" name="name" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Slug</label>
            <input type="text" name="slug" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="image_upload" class="block text-gray-700 font-semibold mb-2">Image</label>
            <input id="image_upload" type="file" name="image" accept="image/*" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Collection</button>
    </form>

    <p id="result" class="mt-6 text-sm text-gray-600"></p>
</div>

<script>
    
document.addEventListener('DOMContentLoaded', function () {
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
    const form = document.getElementById('collection-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('/api/collections/create', {
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