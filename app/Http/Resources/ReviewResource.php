<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_review'   => $this->id_review,
            'id_pesanan'  => $this->id_pesanan,
            'id_user'     => $this->id_user,
            'id_properti' => $this->id_properti,
            'rating'      => $this->rating,
            'komentar'    => $this->komentar,
            'user'        => new UserResource($this->whenLoaded('user')),
        ];
    }
}
