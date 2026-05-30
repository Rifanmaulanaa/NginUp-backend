<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_properti'     => $this->id_properti,
            'id_user'         => $this->id_user,
            'nama_properti'   => $this->nama_properti,
            'tipe_properti'   => $this->tipe_properti,
            'deskripsi'       => $this->deskripsi,
            'alamat'          => $this->alamat,
            'kota'            => $this->kota,
            'provinsi'        => $this->provinsi,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'harga_per_malam' => $this->harga_per_malam,
            'max_tamu'        => $this->max_tamu,
            'jumlah_ruang'    => $this->jumlah_ruang,
            'status'          => $this->status,
            'verified_status' => $this->verified_status,
            'user'            => new UserResource($this->whenLoaded('user')),
            'gambar'          => GambarResource::collection($this->whenLoaded('gambar')),
            'fasilitas'       => FasilitasResource::collection($this->whenLoaded('fasilitas')),
            'aturan'          => AturanResource::collection($this->whenLoaded('aturan')),
            'ketersediaan'    => KetersediaanPropertiResource::collection($this->whenLoaded('ketersediaan')),
            'review'          => ReviewResource::collection($this->whenLoaded('review')),
        ];
    }
}
