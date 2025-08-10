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
            <select id="color_id" name="color_id" class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="size_id" class="block text-sm font-medium text-gray-700 mb-1">Size</label>
            <select id="size_id" name="size_id" class="w-full border rounded-lg p-2">
                <!-- Options go here -->
            </select>
        </div>

        <div>
            <label for="fabric_id" class="block text-sm font-medium text-gray-700 mb-1">Fabric</label>
            <select id="fabric_id" name="fabric_id" class="w-full border rounded-lg p-2">
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const colorSelect = document.getElementById("color_id");
    const sizeSelect = document.getElementById("size_id");
    const fabricSelect = document.getElementById("fabric_id");
    const imageInput = document.getElementById("image_upload");
    const imageUrlInput = document.getElementById("image_url");
    const preview = document.getElementById("preview-image");
    const resultDiv = document.getElementById("result");

    // Load options dynamically
    async function loadOptions(url, selectElement) {
        const res = await fetch(url);
        const data = await res.json();
        data.data.forEach(item => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.name;
            selectElement.appendChild(option);
        });
    }

    loadOptions("/api/colors", colorSelect);
    loadOptions("/api/sizes", sizeSelect);
    loadOptions("/api/fabrics", fabricSelect);

    // Upload image to Cloudinary
    imageInput.addEventListener("change", async function () {
        const file = imageInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("image", file);

        const res = await fetch("/image/upload", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        const data = await res.json();
        imageUrlInput.value = data.url;
        preview.innerHTML = `<img src="${data.url}" alt="Preview" class="mt-2 h-24 rounded-lg shadow-md">`;
    });

    // Submit form
    document.getElementById("variant-form").addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        const productId = $productId ?? 1 // Replace with dynamic variable if needed
        const res = await fetch(`/products/${productId}/variants/create`, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': formData.get('_token')
            },
            body: formData
        });

        const data = await res.json();
        if (res.ok) {
            resultDiv.innerHTML = `<span class="text-green-600 font-semibold">✅ ${data.message}</span>`;
        } else {
            resultDiv.innerHTML = `<span class="text-red-600 font-semibold">❌ ${data.error || 'Failed to create variant.'}</span>`;
        }
    });
});
</script>

@endsection