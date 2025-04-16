@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="page-inner">
        <h2>Tambah absen</h2>
    <a href="{{ route('absensi.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_absen" class="form-label">Nama absen</label>
            <input type="text" name="nama_absen" class="form-control @error('nama_absen') is-invalid @enderror" required>
            @error('nama_absen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
    </div>
</div>
@endsection
