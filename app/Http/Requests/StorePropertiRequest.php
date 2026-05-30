<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_properti'   => 'required|string|max:255',
            'tipe_properti'   => 'required|string|max:50',
            'deskripsi'       => 'nullable|string',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'harga_per_malam' => 'required|numeric|min:0',
            'max_tamu'        => 'required|integer|min:1',
            'jumlah_ruang'    => 'nullable|integer|min:1',
            'status'          => 'sometimes|in:draft,active,inactive',
        ];
    }
}
