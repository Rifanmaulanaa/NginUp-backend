<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KetersediaanPropertiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_ketersediaan'     => $this->id_ketersediaan,
            'id_properti'         => $this->id_properti,
            'blocked_from'        => $this->blocked_from,
            'blocked_until'       => $this->blocked_until,
            'status_ketersediaan' => $this->status_ketersediaan,
        ];
    }
}
