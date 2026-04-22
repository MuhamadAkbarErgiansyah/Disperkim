<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * User Controller
 * 
 * Mengelola operasi CRUD untuk User
 */
class UserController extends Controller
{
    /**
     * Get all users
     * 
     * Mengambil semua data user dengan pagination
     * 
     * @queryParam page integer Halaman (default: 1)
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = User::paginate($request->get('per_page', 15));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'data' => $users->items(),
            'meta' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage()
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Create new user
     * 
     * Membuat user baru
     * 
     * @bodyParam name string required Nama user
     * @bodyParam email string required Email user (unique)
     * @bodyParam password string required Password minimal 8 karakter
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_CREATED);
    }

    /**
     * Get user by ID
     * 
     * Mengambil detail user berdasarkan ID
     * 
     * @urlParam id integer required ID user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Update user
     * 
     * Mengupdate data user berdasarkan ID
     * 
     * @urlParam id integer required ID user
     * @bodyParam name string Nama user
     * @bodyParam email string Email user
     * @bodyParam password string Password baru (minimal 8 karakter)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed'
        ]);

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Delete user
     * 
     * Menghapus user berdasarkan ID
     * 
     * @urlParam id integer required ID user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
}
