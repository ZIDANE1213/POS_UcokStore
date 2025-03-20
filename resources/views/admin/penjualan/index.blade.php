@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Penjualan</h1>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Tambah Penjualan</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Total Bayar</th>
                    <th>Pelanggan</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $pj)
                    <tr>
                        <td>{{ $pj->no_faktur }}</td>
                        <td>{{ $pj->tgl_faktur->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($pj->total_bayar, 0, ',', '.') }}</td>
                        <td>{{ $pj->pelanggan->nama ?? 'Umum' }}</td>
                        <td>{{ ucfirst($pj->metode_pembayaran) }}</td>
                        <td>{{ ucfirst($pj->status_pembayaran) }}</td>
                        <td>
                            <a href="{{ route('penjualan.show', $pj->id) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
