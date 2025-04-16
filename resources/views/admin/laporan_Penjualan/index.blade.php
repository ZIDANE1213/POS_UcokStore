@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="page-inner">
            <h2 class="mb-4">Laporan Penjualan</h2>
        
            <a href="{{ route('laporan.Penjualan.pdf') }}" class="btn btn-danger mb-3" target="_blank">
                Download PDF
            </a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penjualans as $index => $penjualan)
                        @php $rowspan = $penjualan->detailPenjualan->count(); @endphp

                        @foreach ($penjualan->detailPenjualan as $i => $detail)
                            <tr>
                                {{-- Nomor transaksi (hanya di baris pertama) --}}
                                @if ($i === 0)
                                    <td rowspan="{{ $rowspan }}">{{ $index + 1 }}</td>
                                @endif

                                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>

                                {{-- Tanggal transaksi (hanya di baris pertama) --}}
                                @if ($i === 0)
                                <td rowspan="{{ $rowspan }}">{{ \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d M Y') }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
