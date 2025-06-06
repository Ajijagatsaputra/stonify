<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $produk = Produk::get();
            return DataTables::of($produk)
                ->editColumn('foto_produk', function ($produk) {
                    return '<img src="' . asset('storage/' . $produk->foto_produk) . '" width="100" height="100">';
                })
                ->addColumn('action', function ($produk) {
                    return '<div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$produk->id.'" data-original-title="Edit" class="edit btn btn-primary btn-xs">Edit</a>
                                    &nbsp;
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$produk->id.'" data-original-title="Delete" class="delete btn btn-danger btn-xs">Delete</a>
                                </div>';
                })
                ->editColumn('created_at', function ($produk) {
                    return $produk->created_at->diffForHumans();
                })
                ->editColumn('updated_at', function ($produk) {
                    return $produk->updated_at ? $produk->updated_at->diffForHumans() : '-';
                })
                ->rawColumns(['foto_produk','action'])
                ->make(true);
        }
        return view('produk.index');
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required|string|max:255',
        'stok' => 'required|integer|min:0',
        'harga' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string',
        'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->only('nama_produk', 'stok', 'harga', 'deskripsi');

    if ($request->hasFile('foto_produk')) {
        $fotoPath = $request->file('foto_produk')->store('produks', 'public');
        $data['foto_produk'] = $fotoPath;
    }

    Produk::create($data);

    return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
}
public function show($id)
{
    $produk = Produk::findOrFail($id);
    return view('produk.show', compact('produk'));
}


    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'nama_produk' => 'required|string|max:255',
        'stok' => 'required|integer',
        'harga' => 'required|integer',
        'deskripsi' => 'nullable|string',
        'foto_produk' => 'nullable|image|max:2048',
    ]);

    $produk = Produk::findOrFail($id); // Cari produk berdasarkan ID

    // Update data produk
    $produk->nama_produk = $request->nama_produk;
    $produk->stok = $request->stok;
    $produk->harga = $request->harga;
    $produk->deskripsi = $request->deskripsi;

    // Handle upload foto
    if ($request->hasFile('foto_produk')) {
        // Hapus foto lama jika ada
        if ($produk->foto_produk && Storage::exists($produk->foto_produk)) {
            Storage::delete($produk->foto_produk);
        }

        $produk->foto_produk = $request->file('foto_produk')->store('produk', 'public');
    }

    $produk->save(); // Simpan perubahan

    return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Delete image if it exists
        if ($produk->foto_produk && Storage::exists($produk->foto_produk)) {
            Storage::delete($produk->foto_produk);
        }

        if ($produk->delete()) {
            return response()->json(['success' => 'Produk berhasil dihapus!']);
        } else {
            return response()->json(['error' => 'Gagal menghapus produk.'], 400);
        }
    }
    
}
