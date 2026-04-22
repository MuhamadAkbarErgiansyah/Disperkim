<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Sampah Controller
 * 
 * Mengelola operasi CRUD untuk data Sampah
 */
class SampahController extends Controller
{
    /**
     * Get all sampah
     * 
     * Mengambil semua data sampah dengan sort by id dan jenis_sampah (10 per page)
     * 
     * @queryParam sort_by string Sort field: 'id' atau 'jenis_sampah' (default: 'id')
     * @queryParam order string Sort order: 'asc' atau 'desc' (default: 'asc')
     * @queryParam page integer Halaman (default: 1)
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'id');
        $order = $request->get('order', 'asc');
        
        // Validasi sort field
        $sortBy = in_array($sortBy, ['id', 'jenis_sampah']) ? $sortBy : 'id';
        $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';

        $sampah = Sampah::orderBy($sortBy, $order)->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data sampah retrieved successfully',
            'data' => $sampah->items(),
            'meta' => [
                'total' => $sampah->total(),
                'per_page' => $sampah->perPage(),
                'current_page' => $sampah->currentPage(),
                'last_page' => $sampah->lastPage(),
                'sort_by' => $sortBy,
                'order' => $order
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Create new sampah
     * 
     * Membuat data sampah baru
     * 
     * @bodyParam id string required ID unik untuk sampah
     * @bodyParam jenis_sampah string required Jenis sampah
     * @bodyParam harga integer required Harga sampah
     * @bodyParam satuan string required Satuan (kg, ton, dll)
     * @bodyParam deskripsi string required Deskripsi sampah
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|unique:sampah,id',
            'jenis_sampah' => 'required|string|max:191',
            'harga' => 'required|integer|min:0',
            'satuan' => 'required|string|max:191',
            'deskripsi' => 'required|string|max:191'
        ]);

        $sampah = Sampah::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Sampah created successfully',
            'data' => $sampah
        ], Response::HTTP_CREATED);
    }

    /**
     * Get sampah by ID
     * 
     * Mengambil detail sampah berdasarkan ID
     * 
     * @urlParam id string required ID sampah
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $sampah = Sampah::find($id);

        if (!$sampah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sampah not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sampah retrieved successfully',
            'data' => $sampah
        ], Response::HTTP_OK);
    }

    /**
     * Update sampah
     * 
     * Mengupdate data sampah berdasarkan ID
     * 
     * @urlParam id string required ID sampah
     * @bodyParam jenis_sampah string Jenis sampah
     * @bodyParam harga integer Harga sampah
     * @bodyParam satuan string Satuan
     * @bodyParam deskripsi string Deskripsi sampah
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $sampah = Sampah::find($id);

        if (!$sampah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sampah not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'jenis_sampah' => 'sometimes|string|max:191',
            'harga' => 'sometimes|integer|min:0',
            'satuan' => 'sometimes|string|max:191',
            'deskripsi' => 'sometimes|string|max:191'
        ]);

        $sampah->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Sampah updated successfully',
            'data' => $sampah
        ], Response::HTTP_OK);
    }

    /**
     * Delete sampah
     * 
     * Menghapus data sampah berdasarkan ID
     * 
     * @urlParam id string required ID sampah
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $sampah = Sampah::find($id);

        if (!$sampah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sampah not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $sampah->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sampah deleted successfully'
        ], Response::HTTP_OK);
    }
}
