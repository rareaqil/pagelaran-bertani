<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FruitType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FruitTypeController extends Controller
{
    /**
     * Halaman utama CRUD Jenis Buah (One Page)
     */
    public function index()
    {
        $fruits = FruitType::orderBy('name')->get();
        return view('backend.fruit-types.index', compact('fruits'));
    }

    /**
     * Simpan data baru atau update jika ada ID
     */
    public function store(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('fruit_types', 'name')->ignore($id)
            ],
        ];

        $validated = $request->validate($rules);

        $validated['slug'] = Str::slug($validated['name']);

        if ($id) {
            // update
            $fruit = FruitType::findOrFail($id);
            $fruit->update($validated);
        } else {
            // create
            $fruit = FruitType::create($validated + ['is_active' => true]);
        }

        return response()->json($fruit);
    }

    /**
     * Toggle aktif/nonaktif
     */
    public function toggle(FruitType $fruitType)
    {
        // cek relasi: tidak boleh menonaktifkan jika masih dipakai
        if ($fruitType->products()->exists() || $fruitType->posts()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menonaktifkan. Masih digunakan oleh produk atau post.'
            ], 422);
        }

        $fruitType->is_active = ! $fruitType->is_active;
        $fruitType->save();

        return response()->json([
            'success' => true,
            'message' => $fruitType->is_active
                ? 'Jenis buah berhasil diaktifkan kembali.'
                : 'Jenis buah berhasil dinonaktifkan.',
            'status'  => $fruitType->is_active,
        ]);
    }

    /**
     * Hard Delete (benar-benar hapus)
     */
    public function destroy(FruitType $fruitType)
    {
        if ($fruitType->products()->exists() || $fruitType->posts()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa dihapus karena masih digunakan.'
            ], 422);
        }

        $fruitType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis buah berhasil dihapus permanen.'
        ]);
    }
}
