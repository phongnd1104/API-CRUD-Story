<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            'name' => $this->name,
            'course' => $this->course,
            'project' => $this->project,
            'type' => $this->type,
            'author' => $this->author->name,
            'illustrator'=>$this->illustrator->name,
            'image' => $this->image->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
