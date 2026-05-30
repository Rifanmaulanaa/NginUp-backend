<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AturanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_aturan'   => $this->id_aturan,
            'text_aturan' => $this->text_aturan,
        ];
    }
}
