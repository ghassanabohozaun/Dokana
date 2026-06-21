<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'details' => $this->details,
            'photo' => $this->photo == null  ? null :  $this->getImageUrl($this->photo , 'pages'),
            'status' => $this->status,
        ];
    }
}
