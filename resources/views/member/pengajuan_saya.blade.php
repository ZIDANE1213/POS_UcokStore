@foreach($pengajuan_barang as $pengajuan)
<tr>
    <td>{{ $pengajuan->nama_barang }}</td>
    <td>{{ $pengajuan->deskripsi }}</td>
    <td>
        <span class="badge 
            {{ $pengajuan->status == 'disetujui' ? 'bg-success' : ($pengajuan->status == 'ditolak' ? 'bg-danger' : 'bg-warning') }}">
            {{ ucfirst($pengajuan->status) }}
        </span>
    </td>
</tr>
@endforeach
