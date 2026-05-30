<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code_pembayaran'   => 'required|string|max:100|unique:pembayaran,code_pembayaran',
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:50',
            'bukti_pembayaran'  => 'nullable|string',
        ];
    }
}
