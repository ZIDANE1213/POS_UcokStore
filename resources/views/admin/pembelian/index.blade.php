@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Transaksi Pembelian</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('pembelian.create') }}" class="btn btn-primary mb-3">Tambah Pembelian</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode Masuk</th>
                    <th>Tanggal</th>
                    <th>Pemasok</th>
                    <th>Total (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelian as $item)
                    <tr>
                        <td>{{ $item->kode_masuk }}</td>
                        <td>{{ $item->tanggal_masuk }}</td>
                        <td>{{ $item->pemasok->nama_pemasok }}</td>
                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('pembelian.show', $item->id) }}" class="btn btn-info">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
