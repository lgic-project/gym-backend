@extends('layouts.app')

@section('content')
    <h1>Edit Product</h1>
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}">
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" class="form-control" value="{{ old('price', $product->price) }}">
        </div>

        <div class="form-group">
            <label for="image">Current Image:</label>
            <div>
                @if($product->getFirstMediaUrl())
                    <img src="{{ $product->getFirstMediaUrl() }}" alt="{{ $product->name }}" class="img-thumbnail" width="150">
                @else
                    <img src="{{ asset('default-image.png') }}" alt="{{ $product->name }}" class="img-thumbnail" width="150">
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="image">New Image:</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Product</button>
        </div>
    </form>
@endsection
