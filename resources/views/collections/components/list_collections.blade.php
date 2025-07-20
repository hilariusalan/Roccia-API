@extends('collections.collection')

@section('content')
<h2 class="mb-3">List of Collections</h2>

    <div class="row"> 
    @forelse ($collections as $collection)
        <div class="col-md-4 mb-4"> 
            <div class="card h-100 shadow-sm">
                <img src="{{ asset($collection->image_url) }}" class="card-img-top" alt="{{ $collection->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $collection->name }}</h5>
                    <p class="card-text text-muted">Slug: {{ $collection->slug }}</p>
                </div>
                <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                No collections found.
            </div>
        </div>
    @endforelse
    @endsection
</div>