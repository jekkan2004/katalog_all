<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // <-- add this at the top of your controller
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return response()->json(
            Produk::with(['gambars', 'fasilitas'])->get()
        );
    }

    public function getAll() {
        return Produk::with(['gambars', 'fasilitas'])->get();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Log::info('Received product data:', $request->all());
        Log::info('Has file gambar:', [$request->hasFile('gambar')]);
        Log::info('File instance:', [$request->file('gambar')]);

        // Log the incoming request data
        Log::info('Received product data:', $request->all());
    
        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'nullable|integer',
            'deskripsi_produk' => 'nullable|string',
            'gambar'           => 'nullable|array',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'fasilitas'        => 'nullable|array',
            'fasilitas.*'      => 'integer|exists:fasilitas,id',
        ]);

        $slugBase = Str::slug($data['nama_produk'], '-');

        $originalSlug = $slugBase;
        $counter = 1;
        while (Produk::where('slug', $originalSlug)->exists()) {
            $originalSlug = $slugBase . '-' . $counter++;
        }

        $produkData = Arr::only($data, ['nama_produk', 'harga_produk', 'deskripsi_produk']);
        $produkData['slug'] = $originalSlug;

        $produk = Produk::create($produkData);

         // proses upload banyak gambar (max 5)
        if ($request->hasFile('gambar')) {
            $files = $request->file('gambar');
            $maxFiles = min(count($files), 5);

            foreach (array_slice($files, 0, $maxFiles) as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('gambar_produk', $filename, 'public');

                $produk->gambars()->create(['path' => $path]);
            }
        }

        if (!empty($data['fasilitas'])) {
            $produk->fasilitas()->sync($data['fasilitas']);
        }

        //Kembalikan response JSON
        return response()->json([
            'message' => 'Produk berhasil disimpan',
            'data' => $produk->load('gambars', 'fasilitas'),
        ], 201);
 
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return response()->json($produk->load('gambars', 'fasilitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        
        Log::info('Update product request:', $request->all());
        Log::info('Has file gambar on update:', [$request->hasFile('gambar')]);

        $validatedData = $request->validate([
            'nama_produk' => 'sometimes|required|string|max:255',
            'harga_produk' => 'sometimes|nullable|numeric',
            'deskripsi_produk' => 'sometimes|nullable|string',
            'gambar' => 'nullable|array',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'fasilitas'        => 'nullable|array',
            'fasilitas.*'      => 'integer|exists:fasilitas,id',
            'remove_gambars'   => 'nullable|array',
            'remove_gambars.*' => 'integer|exists:produk_gambars,id',
        ]);

        // Jika nama_produk dikirim, update slug
        if (array_key_exists('nama_produk', $validatedData)) {
            $slugBase = Str::slug($validatedData['nama_produk'], '-');
            $slug = $slugBase;
            $counter = 1;
            while (Produk::where('slug', $slug)->where('id', '!=', $produk->id)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }
            $validatedData['slug'] = $slug;
        }

        //Hpus Gambar
        if (!empty($validatedData['remove_gambars'])) {
            foreach ($validatedData['remove_gambars'] as $gId) {
                $g = $produk->gambars()->where('id', $gId)->first();
                if ($g) {
                    // hapus file fisik
                    Storage::disk('public')->delete($g->path);
                    // hapus record
                    $g->delete();
                }
            }
        }
        
        // Handle Gambar
        if ($request->hasFile('gambar')) {
            $existingCount = $produk->gambars()->count();
            $allowed = max(0, 5 - $existingCount);
    
            if ($allowed > 0) {
                $files = array_slice($request->file('gambar'), 0, $allowed);
                foreach ($files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('gambar_produk', $filename, 'public');
                    $produk->gambars()->create(['path' => $path]);
                }
            }
        }

        //singkronasi fasilitas
        if (array_key_exists('fasilitas', $validatedData)) {
            $produk->fasilitas()->sync($validatedData['fasilitas'] ?? []);
        }

        // Update model
        $updateData = Arr::only($validatedData, ['nama_produk', 'harga_produk', 'deskripsi_produk', 'slug']);
        try {
            if (!empty($updateData)) $produk->update($updateData);

            return response()->json([
                'message' => 'Produk berhasil diupdate',
                'data' => $produk->load('gambars', 'fasilitas'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('gagal Update Produk', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Gagal mengupdate produk'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

     // >>> ADDED: delete single gambar dari produk (dipanggil dari FE saat user klik X pada existing image)
    public function destroyGambar(Produk $produk, $gambarsId)
    {
        $g = $produk->gambars()->where('id', $gambarsId)->first();
        if (! $g) {
            return response()->json(['message' => 'Gambar tidak ditemukan'], 404);
        }
        Storage::disk('public')->delete($g->path);
        $g->delete();
        return response()->json(['message' => 'Gambar dihapus'], 200);
    }

    public function destroy(Produk $produk)
    {
        // hapus semua file gambar dan record gambars
        foreach ($produk->gambars as $g) {
            Storage::disk('public')->delete($g->path);
            $g->delete();
        }

        // detach fasilitas
        $produk->fasilitas()->detach();

        // hapus produk
        $produk->delete();

        return response()->json(null, 204);
    }

}
