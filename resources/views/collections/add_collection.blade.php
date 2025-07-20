<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Collection</title>
</head>
<body>
    <h2 class="mb-3">List of Collections</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('collections.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Collection Image Url</label>
            <input type="text" name="image_url" class="form-control" id="image_url" required>
            <label for="name" class="form-label">Collection Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
            <label for="name" class="form-label">Collection Slug</label>
            <input type="text" name="slug" class="form-control" id="slug" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            @error('slug')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            @error('image_url')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</body>
</html>