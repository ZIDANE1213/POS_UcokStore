@extends('layouts.admin')

@section('content')
<<<<<<< HEAD
    <div class="container">
        <h2 class="mb-4">Tambah Penjualan</h2>

        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="tgl_faktur" class="form-label">Tanggal Faktur</label>
                <input type="date" class="form-control" name="tgl_faktur" required>
            </div>

            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="col-md-3">
                        <div class="card"
                            onclick="addToCart({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->harga_jual }}, {{ $barang->stok }})">
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
                </tbody>
            </table>

            <input type="hidden" name="barang" id="barangData">

            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
        </form>
    </div>

    <script>
        let cart = [];

        function addToCart(id, name, price, stock) {
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
                    id,
                    name,
                    price,
                    jumlah: 1,
                    subtotal: price
                });
            }
            updateCart();
        }

        function updateCart() {
            let tableBody = document.querySelector("#cartTable tbody");
            tableBody.innerHTML = "";
            let total = 0;
            cart.forEach((item, index) => {
                item.subtotal = item.price * item.jumlah;
                total += item.subtotal;
                tableBody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>Rp ${item.price.toLocaleString()}</td>
                    <td>
                        <input type="number" value="${item.jumlah}" min="1" max="${item.stock}" onchange="updateQuantity(${index}, this.value)">
                    </td>
                    <td>Rp ${item.subtotal.toLocaleString()}</td>
                    <td><button class="btn btn-danger" onclick="removeFromCart(${index})">Hapus</button></td>
                </tr>
            `;
            });
            document.getElementById("barangData").value = JSON.stringify(cart);
        }

        function updateQuantity(index, value) {
            cart[index].jumlah = parseInt(value);
            updateCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }
    </script>
=======
<div class="container">
    <h2 class="mb-4">Tambah Penjualan</h2>

    <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tgl_faktur" class="form-label">Tanggal Faktur</label>
            <input type="date" class="form-control" name="tgl_faktur" required>
        </div>

        <div class="row">
            @foreach ($barangs as $barang)
                <div class="col-md-3">
                    <div class="card" onclick="addToCart({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->harga_jual }}, {{ $barang->stok }})">
                        <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top" alt="{{ $barang->nama_barang }}">
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
            <tbody></tbody>
        </table>

        <input type="hidden" name="barang" id="barangData">

        <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
    </form>
</div>

<script>
    let cart = [];

    function addToCart(id, name, price, stock) {
        let existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.jumlah < stock) {
                existing.jumlah++;
            } else {
                alert("Stok tidak mencukupi!");
                return;
            }
        } else {
            cart.push({ id, name, price, jumlah: 1, subtotal: price });
        }
        updateCart();
    }

    function updateCart() {
        let tableBody = document.querySelector("#cartTable tbody");
        tableBody.innerHTML = "";
        let total = 0;
        cart.forEach((item, index) => {
            item.subtotal = item.price * item.jumlah;
            total += item.subtotal;
            tableBody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>Rp ${item.price.toLocaleString()}</td>
                <td>
                    <input type="number" value="${item.jumlah}" min="1" max="${item.stock}" onchange="updateQuantity(${index}, this.value)">
                </td>
                <td>Rp ${item.subtotal.toLocaleString()}</td>
                <td><button class="btn btn-danger" onclick="removeFromCart(${index})">Hapus</button></td>
            </tr>`;
        });
        document.getElementById("barangData").value = JSON.stringify(cart);
    }

    function updateQuantity(index, value) {
        cart[index].jumlah = parseInt(value);
        updateCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCart();
    }
</script>
>>>>>>> e5d46b5 (Menambahkan project baru)
@endsection
