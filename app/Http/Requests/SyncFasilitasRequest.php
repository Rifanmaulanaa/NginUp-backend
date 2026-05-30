<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncFasilitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_fasilitas'   => 'required|array',
            'id_fasilitas.*' => 'integer|exists:fasilitas,id_fasilitas',
        ];
    }
}
