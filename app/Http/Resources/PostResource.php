<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $tags = $this->tags->map(function ($item) {
            return $item->name;
        });


        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'body'   => $this->body,
            'image'  => url('/storage/' . $this->image),
            'user'   => $this->user->name,
            'tags'   => $tags,
            'pinned' => $this->pinned == 1 ? 'yes' : 'no',
        ];
    }
}
