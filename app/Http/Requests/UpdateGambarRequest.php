<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGambarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url_gambar' => 'sometimes|string',
            'is_primary' => 'sometimes|boolean',
            'urutan'     => 'sometimes|integer|min:0',
        ];
    }
}
