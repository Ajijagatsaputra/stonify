<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::latest()->get();
        return view('artikels.index', compact('artikels'));
    }

    public function create()
    {
        return view('artikels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'status' => 'required',
            'kategori' => 'required',
            'tags' => 'required',
            'deskripsi_singkat' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $slug = Str::slug($request->judul);
        $data = $request->only(['judul', 'konten', 'status', 'kategori', 'tags', 'deskripsi_singkat']);
        $data['slug'] = $slug;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('uploads/artikel', 'public');
        }

        Artikel::create($data);

        return redirect()->route('artikels.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Artikel $artikel)
    {
        return view('artikels.edit', compact('artikel'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'status' => 'required',
            'kategori' => 'required',
            'tags' => 'required',
            'deskripsi_singkat' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $slug = Str::slug($request->judul);
        $data = $request->only(['judul', 'konten', 'status', 'kategori', 'tags', 'deskripsi_singkat']);
        $data['slug'] = $slug;

        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('uploads/artikel', 'public');
        }

        $artikel->update($data);

        return redirect()->route('artikels.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy($artikel)
    {
        $artikel = Artikel::find($artikel);
        $artikel->delete();
        return response()->json(['success' => true]);
    }

}
