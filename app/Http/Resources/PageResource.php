<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $textPageResources = [];
        foreach ($this->textpages as $textpage) {
            $textPageResources[] = new TextPageResouce($textpage);
        }

        return [
            'page_id' => $this->id,
            'story' => $this->story->name,
            'background' => $this->image->image,
            'config' => $textPageResources
        ];
    }
}
