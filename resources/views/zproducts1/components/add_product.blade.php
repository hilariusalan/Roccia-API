@extends('products.product')

@section('content')
<div class="container">
    <h2>Create Product</h2>

    <form id="product-form">
        @csrf

        <div class="mb-3">
            <label for="name">Product Name</label>
            <input type="text" id="name" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label for="slug">Slug</label>
            <input type="text" id="slug" class="form-control" name="slug" required>
        </div>

        <div class="mb-3">
            <label for="price">Price</label>
            <input type="number" id="price" class="form-control" name="price" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea id="description" class="form-control" name="description" required></textarea>
        </div>

        <div class="mb-3">
            <label for="collection_id">Collection</label>
            <select id="collection_id" class="form-control" name="collection_id" required></select>
        </div>

        <div class="mb-3">
            <label for="type_id">Type</label>
            <select id="type_id" class="form-control" name="type_id" required></select>
        </div>

        <div class="mb-3">
            <label for="image_upload">Upload Image</label>
            <input type="file" id="image_upload" accept="image/*" class="form-control">
            <input type="hidden" name="image_url" id="image_url">
            <div id="preview-image" class="mt-2"></div>
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>

    <div id="result" class="mt-4"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collectionSelect = document.getElementById('collection_id');
    const typeSelect = document.getElementById('type_id');
    const form = document.getElementById('product-form');

    // Fetch collections
    axios.get('/api/collections')
        .then(res => {
            res.data.data.forEach(collection => {
                const option = document.createElement('option');
                option.value = collection.id;
                option.textContent = collection.name;
                collectionSelect.appendChild(option);
            });
        });

    // Fetch types
    axios.get('/api/types')
        .then(res => {
            res.data.data.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = type.name;
                typeSelect.appendChild(option);
            });
        });

    // Upload image to Cloudinary
    document.getElementById('image_upload').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('upload_preset', 'your_unsigned_preset'); // Ganti dengan upload preset kamu

        axios.post('https://api.cloudinary.com/v1_1/your_cloud_name/image/upload', formData)
            .then(res => {
                const imageUrl = res.data.secure_url;
                document.getElementById('image_url').value = imageUrl;
                document.getElementById('preview-image').innerHTML = `<img src="${imageUrl}" width="200">`;
            })
            .catch(err => {
                alert('Upload gagal');
            });
    });

    // Submit form
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const data = new FormData(form);

        axios.post('/api/products/create', Object.fromEntries(data))
            .then(res => {
                document.getElementById('result').innerHTML = `<div class="alert alert-success">${res.data.message}</div>`;
                form.reset();
            })
            .catch(err => {
                document.getElementById('result').innerHTML = `<div class="alert alert-danger">${err.response.data.message || 'Gagal menyimpan produk'}</div>`;
            });
    });
});
</script>
@endsection
