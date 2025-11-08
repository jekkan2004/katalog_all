<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::all();
        return response()->json($fasilitas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
        ]);

        $fasilitas = Fasilitas::create($request->all());
        return response()->json(['message' => 'Fasilitas berhasil ditambahkan', 'data' => $fasilitas]);
    }

    public function update(Request $request, Fasilitas $fasilitas)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
        ]);

        $fasilitas->update($request->all());
        return response()->json(['message' => 'Fasilitas berhasil diupdate', 'data' => $fasilitas]);
    }

    public function destroy(Fasilitas $fasilitas)
    {
        $fasilitas->delete();
        return response()->json(['message' => 'Fasilitas dihapus']);
    }

}
