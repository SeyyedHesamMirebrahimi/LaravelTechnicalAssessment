<?php

namespace App\Http\Resources\Api;

use App\Extensions\TokenUserProvider;
use App\Http\Resources\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LabelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userProvider = new TokenUserProvider();
        $user=  $userProvider->getUserByRequest($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tasks' => $this->tasks()->where('user_id' , $user->id)->count(),
        ];
    }
}
