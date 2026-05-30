<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GambarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_gambar'   => $this->id_gambar,
            'id_properti' => $this->id_properti,
            'url_gambar'  => $this->url_gambar,
            'is_primary'  => $this->is_primary,
            'urutan'      => $this->urutan,
        ];
    }
}
