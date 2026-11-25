<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with('mediaCollection')->latest()->get();

        return UserResource::collection($users);
    }

    public function show(string $id)
    {
        $user = User::with('mediaCollection')->findOrFail($id);

        return new UserResource($user);
    }


    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return (new UserResource($user))
            ->additional([
                'message' => 'Pengguna berhasil ditambahkan.',
            ])
            ->response()
            ->setStatusCode(201);
    }
}
