<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKetersediaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blocked_from'        => 'required|date',
            'blocked_until'       => 'required|date|after_or_equal:blocked_from',
            'status_ketersediaan' => 'sometimes|in:blocked,booked',
        ];
    }
}
