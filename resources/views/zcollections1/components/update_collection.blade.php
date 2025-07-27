@extends('collections.collection')

@section('content')
<div class="container">
    <h1>Edit Collection</h1>

    <form action="{{ route('collections.updateCollection', $collection->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="image_url" class="form-label">Collection Image Url</label>
            <input type="text" name="image_url" class="form-control" id="image_url" value="{{ old('image_url', $collection->image_url) }}" required>
            <label for="name" class="form-label">Collection Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $collection->name) }}" required>
            <label for="slug" class="form-label">Collection Slug</label>
            <input type="text" name="slug" class="form-control" id="slug" value="{{ old('name', $collection->slug) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('collections.collection') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection