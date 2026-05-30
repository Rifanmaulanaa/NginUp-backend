<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KonfirmasiPembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_pembayaran' => 'required|in:paid,failed',
        ];
    }
}
