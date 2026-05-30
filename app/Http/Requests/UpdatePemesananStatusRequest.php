<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePemesananStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_pemesanan' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
        ];
    }
}
