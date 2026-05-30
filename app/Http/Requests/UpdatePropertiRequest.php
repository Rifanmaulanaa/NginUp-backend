<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_properti'   => 'sometimes|string|max:255',
            'tipe_properti'   => 'sometimes|string|max:50',
            'deskripsi'       => 'nullable|string',
            'alamat'          => 'sometimes|string',
            'kota'            => 'sometimes|string|max:100',
            'provinsi'        => 'sometimes|string|max:100',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'harga_per_malam' => 'sometimes|numeric|min:0',
            'max_tamu'        => 'sometimes|integer|min:1',
            'jumlah_ruang'   => 'nullable|integer|min:1',
            'status'         => 'sometimes|in:draft,active,inactive',
        ];
    }
}
