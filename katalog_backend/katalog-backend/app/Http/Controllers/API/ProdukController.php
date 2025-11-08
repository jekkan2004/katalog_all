<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // <-- add this at the top of your controller
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();
        $produks = Produk::with(['gambars', 'fasilitas'])->get();
        return response()->json($produk);
    }

    public function getAll() {
        return Produk::all();
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
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['slug'] = Str::slug($data['nama_produk'], '-');

        $originalSlug = $data['slug'];
        $counter = 1;
        while (Produk::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        $produk = Produk::create($data);

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

        if ($request->has('fasilitas')) {
            $fasilitas = $request->fasilitas;
        
            // Jika frontend kirim string JSON seperti "[5,6,7]"
            if (is_string($fasilitas)) {
                $fasilitas = json_decode($fasilitas, true);
            }
        
            $produk->fasilitas()->sync($fasilitas);
        }

        //Kembalikan response JSON
        return response()->json([
            'message' => 'Produk berhasil disimpan',
            'data' => $produk->load('gambars'),
            'data' => $produk,
        ], 201);


        //GAMBAR SEBELUMNYA
        // if ($request->hasFile('gambar')) {
        //     Log::info('Processing uploaded file for gambar');
        //     $file = $request->file('gambar');
        //     $filename = time() . '_' . $file->getClientOriginalName();
    
        //     // Pastikan folder ada
        //     $destinationPath = public_path('temp');
        //     if (!file_exists($destinationPath)) {
        //         mkdir($destinationPath, 0777, true);
        //     }
    
        //     // Simpan file
        //     $file->move($destinationPath, $filename);
        //     Log::info('File uploaded successfully:', ['filename' => $destinationPath]);
        //     // Simpan path relatif agar mudah dipakai di frontend
        //     $data['gambar'] = 'temp/' . $filename;
        // }
        // // Log after validation
        // Log::info('Validated product data:', $data);
    
        // try {
        //     $produk = Produk::create($data);
        //     // Log successful creation
        //     Log::info('Product created successfully:', ['id' => $produk->id, 'slug' => $produk->slug]);
    
        //     return response()->json($produk, 201);
        // } catch (\Exception $e) {
        //     // Log if any error happens
        //     Log::error('Failed to create product:', ['error' => $e->getMessage()]);

        //     Log::error('Failed to create product:', [
        //         'error' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString(),
        //         'data' => $data
        //     ]);

        //     return response()->json(['message' => 
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        $produks = Produk::with(['gambars', 'fasilitas'])->get();
        return response()->json($produk);
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
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika nama_produk dikirim, update slug
        if (array_key_exists('nama_produk', $validatedData)) {
            $validatedData['slug'] = Str::slug($validatedData['nama_produk'], '-');
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

        // Update model
        try {
            $produk->update($validatedData);

            return response()->json([
                'message' => 'Produk berhasil diupdate',
                'data' => $produk->load('gambars'),
                'data' => $produk,
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
    public function destroy(Produk $produk)
    {
        if ($produk->gambar) {
            \Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();
        return response()->json(null, 204);
    }
}
