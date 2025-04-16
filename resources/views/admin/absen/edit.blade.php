@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="page-inner">
            <h2>Edit absen</h2>
            <a href="{{ route('absensi.index') }}" class="btn btn-secondary mb-3">Kembali</a>

            <form action="{{ route('absensi.update', $absen->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_absensi" class="form-label">Nama absen</label>
                    <input type="text" name="nama_absensi" value="{{ $absen->nama_absensi }}"
                        class="form-control @error('nama_absensi') is-invalid @enderror" required>
                    @error('nama_absensi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
@endsection
