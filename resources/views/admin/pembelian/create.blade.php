@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Tambah Transaksi Pembelian</h2>

        <form action="{{ route('pembelian.store') }}" method="POST">
            @csrf

            <!-- Pilih Pemasok -->
            <div class="mb-3">
                <label for="pemasok_id" class="form-label">Pemasok</label>
                <select name="pemasok_id" id="pemasok_id" class="form-control" required>
                    <option value="">-- Pilih Pemasok --</option>
                    @foreach ($pemasoks as $pemasok)
                        <option value="{{ $pemasok->id }}">{{ $pemasok->nama_pemasok }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Pembelian -->
            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control" required>
            </div>

            <!-- Tambah Barang -->
            <h4 class="mt-4">Daftar Barang</h4>
            <table class="table table-bordered" id="barang-table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Harga Beli</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <button type="button" class="btn btn-primary" onclick="addBarang()">Tambah Barang</button>

            <!-- Total Harga -->
            <div class="mt-3">
                <h5>Total: <span id="total-harga">0</span></h5>
            </div>

            <!-- Tombol Simpan -->
            <button type="submit" class="btn btn-success mt-3">Simpan Transaksi</button>
        </form>
    </div>

    <script>
        let barangs = @json($barangs);
        let totalHarga = 0;

        function addBarang() {
            let rowId = Date.now(); // ID unik untuk setiap baris
            let barangOptions = barangs.map(barang => `<option value="${barang.id}">${barang.nama_barang}</option>`).join(
                '');

            let row = `
                <tr id="row-${rowId}">
                    <td>
                        <select name="barang[${rowId}][id]" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            ${barangOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="barang[${rowId}][harga_beli]" class="form-control harga-beli" required min="1" oninput="updateSubtotal(${rowId})">
                    </td>
                    <td>
                        <input type="number" name="barang[${rowId}][jumlah]" class="form-control jumlah" required min="1" oninput="updateSubtotal(${rowId})">
                    </td>
                    <td>
                        <span id="subtotal-${rowId}">0</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="removeBarang(${rowId})">Hapus</button>
                    </td>
                </tr>
            `;

            document.querySelector("#barang-table tbody").insertAdjacentHTML("beforeend", row);
        }

        function updateSubtotal(rowId) {
            let hargaBeli = document.querySelector(`input[name="barang[${rowId}][harga_beli]"]`).value || 0;
            let jumlah = document.querySelector(`input[name="barang[${rowId}][jumlah]"]`).value || 0;
            let subtotal = hargaBeli * jumlah;

            document.querySelector(`#subtotal-${rowId}`).innerText = subtotal;
            updateTotalHarga();
        }

        function removeBarang(rowId) {
            document.querySelector(`#row-${rowId}`).remove();
            updateTotalHarga();
        }

        function updateTotalHarga() {
            let subtotals = document.querySelectorAll('[id^="subtotal-"]');
            totalHarga = Array.from(subtotals).reduce((sum, el) => sum + parseFloat(el.innerText), 0);
            document.querySelector("#total-harga").innerText = totalHarga;
        }
    </script>
@endsection
