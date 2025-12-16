@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Transaksi</h1>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                               @forelse ($transactions as $transaction)
<tr>
    <td>{{ $transaction->product['name'] ?? 'Produk tidak tersedia' }}</td>

    <td>
        Rp {{ number_format($transaction->product['price'] ?? 0, 0, ',', '.') }}
    </td>

    <td>
        @if ($transaction->status == 'pending')
            <span class="badge bg-warning text-dark">pending</span>
        @elseif ($transaction->status == 'success')
            <span class="badge bg-success">success</span>
        @else
            <span class="badge bg-danger">{{ $transaction->status }}</span>
        @endif
    </td>

    <td>{{ $transaction->created_at }}</td>

    <td>
        @if ($transaction->status == 'pending' && $transaction->product)
            <form action="{{ route('checkout-process') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $transaction->id }}">
                <input type="hidden" name="product_id" value="{{ $transaction->product['id'] }}">
                <input type="hidden" name="price" value="{{ $transaction->product['price'] }}">
                <button type="submit" class="btn btn-primary">Bayar</button>
            </form>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center">Tidak ada transaksi</td>
</tr>
@endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
