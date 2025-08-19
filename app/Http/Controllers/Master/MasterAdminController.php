<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MasterAdminController extends Controller
{
    /**
     * Display a listing of all administrators.
     */
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'super-admin', 'operator'])->get();
        return response()->json($admins);
    }

    /**
     * Store a newly created admin user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|confirmed',
            'role' => ['sometimes', Rule::in(['admin', 'super-admin', 'operator'])],
        ]);

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Admin berhasil ditambahkan.', 'user' => $user], 201);
    }

    /**
     * Display a specific admin user.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!in_array($user->role, ['admin', 'super-admin', 'operator'])) {
            return response()->json(['message' => 'Bukan data admin.'], 404);
        }

        return response()->json($user);
    }

    /**
     * Update a specific admin user.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!in_array($user->role, ['admin', 'super-admin', 'operator'])) {
            return response()->json(['message' => 'Bukan data admin.'], 404);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'sometimes|required|string|max:20',
            'password' => 'nullable|string|confirmed',
            'role' => ['sometimes', Rule::in(['admin', 'super-admin', 'operator'])],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'Admin berhasil diperbarui.', 'user' => $user]);
    }

    /**
     * Delete a specific admin user.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!in_array($user->role, ['admin', 'super-admin', 'operator'])) {
            return response()->json(['message' => 'Bukan data admin.'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Admin berhasil dihapus.']);
    }
}