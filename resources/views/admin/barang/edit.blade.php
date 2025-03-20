@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Edit Barang</h2>
        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ $kat->id == $barang->kategori_id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
            </div>
            <div class="mb-3">
                <label for="harga_jual" class="form-label">Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" value="{{ $barang->harga_jual }}" required>
            </div>
            <div class="mb-3">
                <label for="stok_minimal" class="form-label">Stok Minimal</label>
                <input type="number" name="stok_minimal" class="form-control" value="{{ $barang->stok_minimal }}" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" name="gambar" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
