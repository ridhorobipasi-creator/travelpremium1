<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::select('id', 'name', 'email', 'role', 'photo_url', 'created_at')->get());
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'email'     => "sometimes|email|unique:users,email,{$id}",
            'role'      => 'sometimes|in:admin,staff,user',
            'password'  => 'sometimes|string|min:6',
            'photo_url' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user->fresh(['id', 'name', 'email', 'role', 'photo_url']));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
