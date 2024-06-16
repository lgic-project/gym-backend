@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12 text-right">
                <a href="{{ route('products.create') }}" class="btn btn-success">Add New</a>
            </div>
        </div>
        <div class="row">
            @foreach ($products as $p)
                <div class="col-md-4">
                    <div class="card mb-4">
                        @if($p->getFirstMediaUrl())
                            <img src="{{ $p->getFirstMediaUrl() }}" class="card-img-top" alt="{{ $p->name }}">
                        @else
                            <img src="{{ asset('default-image.png') }}" class="card-img-top" alt="{{ $p->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->name }}</h5>
                            <p class="card-text">{{ $p->description }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ $p->price }}</p>
                            <a href="{{ route('products.edit', $p->id) }}" class="btn btn-info">Edit</a>
                            <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
