<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_pesanan'  => 'required|integer|exists:pemesanan,id_pesanan',
            'id_properti' => 'required|integer|exists:properti,id_properti',
            'rating'      => 'required|integer|min:1|max:5',
            'komentar'    => 'nullable|string',
        ];
    }
}
