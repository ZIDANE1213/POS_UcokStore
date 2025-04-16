@extends('layouts.admin')

@section('content')

    <div class="container">
        <h2 class="mb-4">Tambah Penjualan</h2>

        {{-- Tampilkan error jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="tgl_faktur" class="form-label">Tanggal Faktur</label>
                <input type="date" class="form-control" name="tgl_faktur" required>
            </div>

            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="col-md-3 mb-3">
                        <div class="card"
                            onclick="addToCart({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->harga_jual }}, {{ $barang->stok }})"
                            style="cursor: pointer;">
                            <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top"
                                alt="{{ $barang->nama_barang }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                                <p class="card-text">Harga: Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                <p class="card-text">Stok: {{ $barang->stok }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <h3 class="mt-4">Barang yang Dipilih</h3>
            <table class="table" id="cartTable">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dinamis dari JavaScript --}}
                </tbody>
            </table>

            <input type="hidden" name="barang" id="barangData">

            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
        </form>
    </div>

    <script>
        let cart = [];

        function addToCart(id, name, harga_jual, stock) {
            let existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.jumlah < stock) {
                    existing.jumlah++;
                } else {
                    alert("Stok tidak mencukupi!");
                    return;
                }
            } else {
                cart.push({
                    id: id,
                    nama: name,
                    harga_jual: harga_jual,
                    jumlah: 1,
                    stock: stock
                });
            }
            updateCart();
        }

        function updateCart() {
            let tableBody = document.querySelector("#cartTable tbody");
            tableBody.innerHTML = "";
            let total = 0;

            cart.forEach((item, index) => {
                let subtotal = item.harga_jual * item.jumlah;
                total += subtotal;

                tableBody.innerHTML += `
                <tr>
                    <td>${item.nama}</td>
                    <td>Rp ${item.harga_jual.toLocaleString()}</td>
                    <td>
                        <input type="number" value="${item.jumlah}" min="1" max="${item.stock}" onchange="updateQuantity(${index}, this.value)">
                    </td>
                    <td>Rp ${(subtotal).toLocaleString()}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Hapus</button></td>
                </tr>
            `;
            });

            // Simpan data ke hidden input
            document.getElementById("barangData").value = JSON.stringify(cart);
        }

        function updateQuantity(index, value) {
            let jumlah = parseInt(value);
            if (jumlah >= 1 && jumlah <= cart[index].stock) {
                cart[index].jumlah = jumlah;
            } else {
                alert("Jumlah tidak valid atau melebihi stok.");
            }
            updateCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }
    </script>
@endsection
