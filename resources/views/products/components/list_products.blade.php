@extends('products.product')

@section('content')
<div class="container">
    <h2 class="mb-4">Product List</h2>

    <form method="GET" action="{{ route('products.components.list_products') }}" class="mb-4">
        <div class="row">
            <!-- Price Range -->
            <div class="col-md-3">
                <label>Min Price</label>
                <input type="number" name="min_price" class="form-control" value="{{ old('min_price', $filters['min_price'] ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Max Price</label>
                <input type="number" name="max_price" class="form-control" value="{{ old('max_price', $filters['max_price'] ?? '') }}">
            </div>

            <!-- Type Dropdown -->
            <div class="col-md-3">
                <label>Product Type</label>
                <select name="type_id" class="form-control">
                    <option value="">-- All Types --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ ($filters['type_id'] ?? '') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Color Dropdown -->
            <div class="col-md-3">
                <label>Color</label>
                <select name="color_id" class="form-control">
                    <option value="">-- All Colors --</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}" {{ ($filters['color_id'] ?? '') == $color->id ? 'selected' : '' }}>
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Apply Filters</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Product List -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Color Variants</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>Rp{{ number_format($product->price) }}</td>
                    <td>{{ $product->types->name ?? '-' }}</td>
                    <td>
                        @foreach($product->productVariants as $variant)
                            <span class="badge badge-info">{{ $variant->color->name }}</span>
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $products->links() }}
    </div>
</div>
@endsection