@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="page-inner">
            <h2>Edit Kategori</h2>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary mb-3">Kembali</a>

            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}"
                        class="form-control @error('nama_kategori') is-invalid @enderror" required>
                    @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
@endsection
