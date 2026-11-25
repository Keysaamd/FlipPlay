<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Media;
use App\Http\Resources\UserResource;
use App\Http\Resources\MediaResource;
use App\Http\Requests\AddMediaToCollectionRequest;

class CollectionController extends Controller
{
    // Menampilkan koleksi media milik user
    public function index(string $userId)
    {
        $user = User::with('mediaCollection')->findOrFail($userId);

        return new UserResource($user);
    }

    // Menambahkan media ke koleksi user
    public function store(AddMediaToCollectionRequest $request, string $userId)
    {
        $validated = $request->validated();

        $user = User::findOrFail($userId);
        $media = Media::findOrFail($validated['media_id']);

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
