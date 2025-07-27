@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Product</h2>

    <form id="edit-product-form">
        @csrf

        <input type="hidden" id="product_id">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" id="name" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Slug</label>
            <input type="text" id="slug" name="slug" class="form-control">
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" id="price" name="price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Collection</label>
            <select id="collection_id" name="collection_id" class="form-control"></select>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <select id="type_id" name="type_id" class="form-control"></select>
        </div>

        <div class="mb-3">
            <label>Product Image</label><br>
            <img id="current-image" src="" alt="Current Image" width="200"><br><br>
            <input type="file" id="image_upload" accept="image/*" class="form-control">
            <input type="hidden" name="image_url" id="image_url">
            <div id="preview-image" class="mt-2"></div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Update Product</button>
            <button type="button" id="delete-product" class="btn btn-danger">Delete Product</button>
        </div>

        <div id="result" class="mt-3"></div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"></script>
<script>
const productId = "{{ $productId }}"; // Pastikan kirim $productId dari route

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('edit-product-form');
    const deleteButton = document.getElementById('delete-product');

    // Get product detail
    axios.get(`/api/products/${productId}`)
        .then(res => {
            const product = res.data.data;
            document.getElementById('product_id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('slug').value = product.slug;
            document.getElementById('price').value = product.price;
            document.getElementById('description').value = product.description;
            document.getElementById('image_url').value = product.usage_image;
            document.getElementById('current-image').src = product.usage_image;

            loadCollections(product.collection);
            loadTypes(product.type);
        });

    // Load collections
    function loadCollections(selectedName = null) {
        axios.get('/api/collections')
            .then(res => {
                const select = document.getElementById('collection_id');
                select.innerHTML = '';
                res.data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    if (item.name === selectedName) option.selected = true;
                    select.appendChild(option);
                });
            });
    }

    // Load types
    function loadTypes(selectedName = null) {
        axios.get('/api/types')
            .then(res => {
                const select = document.getElementById('type_id');
                select.innerHTML = '';
                res.data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    if (item.name === selectedName) option.selected = true;
                    select.appendChild(option);
                });
            });
    }

    // Image upload
    document.getElementById('image_upload').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('upload_preset', 'your_unsigned_preset'); // <- ganti sesuai Cloudinary kamu

        axios.post('https://api.cloudinary.com/v1_1/your_cloud_name/image/upload', formData)
            .then(res => {
                const imageUrl = res.data.secure_url;
                document.getElementById('image_url').value = imageUrl;
                document.getElementById('preview-image').innerHTML = `<img src="${imageUrl}" width="200">`;
            })
            .catch(() => alert('Gagal upload gambar'));
    });

    // Update product
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        axios.put(`/api/products/${productId}/update`, data)
            .then(res => {
                document.getElementById('result').innerHTML = `<div class="alert alert-success">${res.data.message}</div>`;
            })
            .catch(err => {
                document.getElementById('result').innerHTML = `<div class="alert alert-danger">${err.response.data.message || 'Gagal update produk'}</div>`;
            });
    });

    // Delete product
    deleteButton.addEventListener('click', function () {
        if (confirm('Yakin ingin menghapus produk ini?')) {
            axios.delete(`/api/products/${productId}/delete`)
                .then(res => {
                    alert(res.data.message);
                    window.location.href = '/products'; // redirect ke list product
                })
                .catch(err => {
                    alert('Gagal menghapus produk');
                });
        }
    });
});
</script>
@endsection
