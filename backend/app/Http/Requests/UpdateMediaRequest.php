<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255|unique:media,title,' . $this->route('medium'),
            'type' => 'sometimes|required|string|max:100',
            'genre' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi.',
            'title.unique' => 'Judul media sudah digunakan.',
            'type.required' => 'Jenis media wajib diisi.',
            'genre.required' => 'Genre media wajib diisi.',
        ];
    }
}
