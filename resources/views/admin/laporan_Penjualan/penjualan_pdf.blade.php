<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <table>
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
                        @if ($i === 0)
                            <td rowspan="{{ $rowspan }}">{{ $index + 1 }}</td>
                        @endif
                        <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        @if ($i === 0)
                            <td rowspan="{{ $rowspan }}">
                                {{ \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d M Y') }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5">Tidak ada data penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
