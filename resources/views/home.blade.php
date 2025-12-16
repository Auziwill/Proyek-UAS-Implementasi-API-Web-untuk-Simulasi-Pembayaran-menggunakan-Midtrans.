@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1>Iphone Semarang</h1>
        <p>Belanja online murah, aman dan nyaman dari berbagai toko online di Semarang.</p>
    </div>
</div>

<div class="row">
    @foreach ($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                
                <div class="card-img-top">
                    <img src="{{ $product->image }}" class="img-fluid" alt="{{ $product->name }}">
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>

                    <p class="card-text text-muted">{{ $product->description }}</p>

                    <p class="card-text text-muted">
                        Rp {{ number_format($product->price) }}
                    </p>

                    <a href="{{ route('product', $product->id) }}"
                       class="btn btn-primary mt-auto">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

