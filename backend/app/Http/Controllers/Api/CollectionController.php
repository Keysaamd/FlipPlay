<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Media;
use App\Http\Resources\UserResource;
use App\Http\Resources\MediaResource;

class CollectionController extends Controller
{
    // Menampilkan koleksi media milik user
    public function index(string $userId)
    {
        $user = User::with('mediaCollection')->findOrFail($userId);

        return new UserResource($user);
    }

    // Menambahkan media ke koleksi user
    public function store(Request $request, string $userId)
    {
        $request->validate([
            'media_id' => 'required|exists:media,id',
        ]);

        $user = User::findOrFail($userId);
        $media = Media::findOrFail($request->media_id);

        if ($user->mediaCollection()->where('media_id', $media->id)->exists()) {
            return response()->json([
                'message' => 'Media sudah ada di koleksi.'
            ], 422);
        }

        $user->mediaCollection()->attach($media->id);

        return response()->json([
            'message' => 'Media berhasil ditambahkan ke koleksi.',
            'data' => new MediaResource($media)
        ], 201);
    }

    // Menghapus media dari koleksi user
    public function destroy(string $userId, string $mediaId)
    {
        $user = User::findOrFail($userId);

        $deleted = $user->mediaCollection()->detach($mediaId);

        if ($deleted === 0) {
            return response()->json([
                'message' => 'Media tidak ditemukan dalam koleksi.'
            ], 404);
        }

        return response()->json([
            'message' => 'Media berhasil dihapus dari koleksi.'
        ]);
    }
}
