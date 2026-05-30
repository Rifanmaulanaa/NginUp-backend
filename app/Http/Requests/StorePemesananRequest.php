<?php

namespace App\Http\Requests;

use App\Models\Properti;
use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_properti'      => 'required|integer|exists:properti,id_properti',
            'id_kamar'         => 'nullable|integer|exists:kamar,id_kamar',
            'tanggal_check_in'  => 'required|date',
            'tanggal_check_out' => 'required|date|after:tanggal_check_in',
            'total_tamu'       => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $properti = Properti::find($this->input('id_properti'));
                    if ($properti && $value > $properti->max_tamu) {
                        $fail('Jumlah tamu melebihi kapasitas maksimal (' . $properti->max_tamu . ')');
                    }
                },
            ],
            'catatan'          => 'nullable|string',
        ];
    }
}
