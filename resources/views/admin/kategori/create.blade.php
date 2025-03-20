@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="page-inner">
        <h2>Tambah Kategori</h2>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" required>
            @error('nama_kategori')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
    </div>
</div>
@endsection
