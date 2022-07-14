<?php

namespace App\Http\Resources\Api;

use App\Extensions\TokenUserProvider;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
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
                    'title' => $page->title,
                    'description' => $page->description,
                    'labels' => new TaskLabelCollection($page->labels()->get()),
                ];
            }),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
