<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_pembayaran'     => $this->id_pembayaran,
            'id_pesanan'        => $this->id_pesanan,
            'code_pembayaran'   => $this->code_pembayaran,
            'jumlah_pembayaran' => $this->jumlah_pembayaran,
            'metode_pembayaran' => $this->metode_pembayaran,
            'bukti_pembayaran'  => $this->bukti_pembayaran,
            'status_pembayaran' => $this->status_pembayaran,
            'tanggal_pembayaran'=> $this->tanggal_pembayaran,
            'expired_at'        => $this->expired_at,
        ];
    }
}
