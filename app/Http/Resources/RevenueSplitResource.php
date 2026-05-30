<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevenueSplitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_revenue'               => $this->id_revenue,
            'id_pesanan'               => $this->id_pesanan,
            'id_user'                  => $this->id_user,
            'jumlah_kotor'             => $this->jumlah_kotor,
            'persentase_biaya_platform'=> $this->persentase_biaya_platform,
            'jumlah_biaya_platform'    => $this->jumlah_biaya_platform,
            'jumlah_pemilik'           => $this->jumlah_pemilik,
            'status'                   => $this->status,
            'settled_at'               => $this->settled_at,
            'pemesanan'                => new PemesananResource($this->whenLoaded('pemesanan')),
        ];
    }
}
