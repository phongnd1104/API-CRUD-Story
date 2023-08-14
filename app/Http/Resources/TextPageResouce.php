<?php

namespace App\Http\Resources;

use App\Models\Text;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextPageResouce extends JsonResource
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
            'positions' => $this->positions,
            'text' => new TextResource($this->text)
        ];
    }
}
