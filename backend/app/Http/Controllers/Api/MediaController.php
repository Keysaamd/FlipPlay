<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;


class MediaController extends Controller
{
     /**
     * Menampilkan seluruh data media.
     */
    public function index(Request $request)
    {
        $query = Media::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Mengambil data media beserta relasi users yang terkait, diurutkan berdasarkan waktu terbaru
        $media = $query->with('users')->latest()->get();

        return MediaResource::collection($media);
    }

    /**
     * Menambahkan data media baru ke database.
     */
    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan detail data media berdasarkan ID.
     */
    public function show(string $id)
    {
        // Mengambil data media beserta relasi users yang terkait berdasarkan ID
        $media = Media::with('users')->findOrFail($id);

        return new MediaResource($media);
    }

    /**
     * Memperbarui data media berdasarkan ID.
     */
    public function update(UpdateMediaRequest $request, string $id)
    {
        $media = Media::findOrFail($id);
        $media->update($request->validated());

        return new MediaResource($media);
    }

    /**
     * Menghapus data media berdasarkan ID.
     */
    public function destroy(string $id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return response()->json([
            'message' => 'Media berhasil dihapus.'
        ]);
    }
}
