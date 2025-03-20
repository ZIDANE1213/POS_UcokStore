@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Detail Penjualan - {{ $penjualan->no_faktur }}</h1>
        <p><strong>Tanggal:</strong> {{ $penjualan->tgl_faktur->format('d-m-Y') }}</p>
        <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? 'Umum' }}</p>
        <p><strong>Total Bayar:</strong> Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ ucfirst($penjualan->metode_pembayaran) }}</p>
        <p><strong>Status Pembayaran:</strong> {{ ucfirst($penjualan->status_pembayaran) }}</p>

        <h3>Detail Barang</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->detailPenjualan as $detail)
                    <tr>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection
