<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGambarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url_gambar' => 'required|string',
            'is_primary' => 'sometimes|boolean',
            'urutan'     => 'sometimes|integer|min:0',
        ];
    }
}
