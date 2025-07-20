<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Collections</title>
</head>
<body>
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
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                No collections found.
            </div>
        </div>
    @endforelse
</div>
</body>
</html>