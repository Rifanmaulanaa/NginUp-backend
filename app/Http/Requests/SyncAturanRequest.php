<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncAturanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_aturan'   => 'required|array',
            'id_aturan.*' => 'integer|exists:aturan,id_aturan',
        ];
    }
}
