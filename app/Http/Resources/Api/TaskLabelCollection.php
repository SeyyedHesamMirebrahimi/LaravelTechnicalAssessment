<?php

namespace App\Http\Resources\Api;

use App\Extensions\TokenUserProvider;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskLabelCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userProvider = new TokenUserProvider();
        $user = $userProvider->getUserByRequest($request);
        return [
            'data' => $this->collection->transform(function($page) use ($user) {
                return [
                    'id' => $page->id,
                    'name' => $page->name,
                    'tasks' => $page->tasks()->where('task.user_id' , $user->id)->count(),
                ];
            }),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
