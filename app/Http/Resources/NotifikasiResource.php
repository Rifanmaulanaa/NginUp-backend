<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_notifikasi' => $this->id_notifikasi,
            'id_user'       => $this->id_user,
            'title'         => $this->title,
            'pesan'         => $this->pesan,
            'type'          => $this->type,
            'is_read'       => $this->is_read,
        ];
    }
}
