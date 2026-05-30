<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFasilitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_fasilitas' => 'required|string|max:255',
            'ikon_fasilitas' => 'nullable|string|max:100',
        ];
    }
}
