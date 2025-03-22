<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
<<<<<<< HEAD
    /**
     * Menampilkan daftar transaksi penjualan
     */
=======
>>>>>>> e5d46b5 (Menambahkan project baru)
    public function index()
    {
        $penjualan = Penjualan::with('pelanggan', 'user')->get();
        return view('admin.penjualan.index', compact('penjualan'));
    }

<<<<<<< HEAD
    /**
     * Menampilkan form tambah penjualan
     */
=======
>>>>>>> e5d46b5 (Menambahkan project baru)
    public function create()
    {
        $barangs = Barang::all();
        return view('admin.penjualan.create', compact('barangs'));
    }

<<<<<<< HEAD
    /**
     * Menyimpan data penjualan
     */
=======
>>>>>>> e5d46b5 (Menambahkan project baru)
    public function store(Request $request)
    {
        $request->validate([
            'tgl_faktur' => 'required|date',
<<<<<<< HEAD
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.harga_jual' => 'required|numeric|min:1',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        // Generate no_faktur otomatis
        $noFaktur = 'FTR' . str_pad(Penjualan::count() + 1, 5, '0', STR_PAD_LEFT);

        // Simpan transaksi penjualan
=======
            'barang' => 'required|string', // Harus string karena dikirim dalam JSON
        ]);

        $barangData = json_decode($request->barang, true);
        
        if (!$barangData || !is_array($barangData)) {
            return back()->with('error', 'Data barang tidak valid!');
        }

        $noFaktur = 'FTR' . str_pad(Penjualan::count() + 1, 5, '0', STR_PAD_LEFT);

>>>>>>> e5d46b5 (Menambahkan project baru)
        $penjualan = Penjualan::create([
            'no_faktur' => $noFaktur,
            'tgl_faktur' => $request->tgl_faktur,
            'total_bayar' => 0,
            'pelanggan_id' => $request->pelanggan_id ?? null,
            'user_id' => Auth::id(),
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'lunas',
        ]);

        $totalPenjualan = 0;

<<<<<<< HEAD
        // Simpan detail penjualan dan update stok barang
        foreach ($request->barang as $barangData) {
            $barang = Barang::findOrFail($barangData['id']);
            $subTotal = $barangData['harga_jual'] * $barangData['jumlah'];
=======
        foreach ($barangData as $item) {
            $barang = Barang::findOrFail($item['id']);

            if ($barang->stok < $item['jumlah']) {
                return back()->with('error', "Stok {$barang->nama_barang} tidak mencukupi!");
            }

            $subTotal = $item['price'] * $item['jumlah'];
>>>>>>> e5d46b5 (Menambahkan project baru)
            $totalPenjualan += $subTotal;

            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'barang_id' => $barang->id,
<<<<<<< HEAD
                'harga_jual' => $barangData['harga_jual'],
                'jumlah' => $barangData['jumlah'],
                'sub_total' => $subTotal,
            ]);

            // Kurangi stok barang
            $barang->update([
                'stok' => $barang->stok - $barangData['jumlah'],
            ]);
        }

        // Update total transaksi penjualan
=======
                'harga_jual' => $item['price'],
                'jumlah' => $item['jumlah'],
                'sub_total' => $subTotal,
            ]);

            $barang->decrement('stok', $item['jumlah']);
        }

>>>>>>> e5d46b5 (Menambahkan project baru)
        $penjualan->update(['total_bayar' => $totalPenjualan]);

        return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil disimpan!');
    }

    public function show(Penjualan $penjualan)
    {
        return view('admin.penjualan.show', compact('penjualan'));
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> e5d46b5 (Menambahkan project baru)
