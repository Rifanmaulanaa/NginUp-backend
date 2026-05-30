<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemesananResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_pesanan'      => $this->id_pesanan,
            'id_user'         => $this->id_user,
            'id_properti'     => $this->id_properti,
            'id_kamar'        => $this->id_kamar,
            'tanggal_check_in'  => $this->tanggal_check_in,
            'tanggal_check_out' => $this->tanggal_check_out,
            'total_malam'       => $this->total_malam,
            'total_tamu'        => $this->total_tamu,
            'total_harga'       => $this->total_harga,
            'status_pemesanan'  => $this->status_pemesanan,
            'catatan_traveler'  => $this->catatan_traveler,
            'user'            => new UserResource($this->whenLoaded('user')),
            'properti'        => new PropertiResource($this->whenLoaded('properti')),
            'pembayaran'      => new PembayaranResource($this->whenLoaded('pembayaran')),
            'review'          => new ReviewResource($this->whenLoaded('review')),
            'revenueSplit'    => new RevenueSplitResource($this->whenLoaded('revenueSplit')),
        ];
    }
}
