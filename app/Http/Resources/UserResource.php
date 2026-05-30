<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_user'    => $this->id_user,
            'nama'       => $this->nama,
            'username'   => $this->username,
            'email'      => $this->email,
            'no_hp'      => $this->no_hp,
            'foto_profil'=> $this->foto_profil,
            'role'       => $this->role,
            'status'     => $this->status,
        ];
    }
}
