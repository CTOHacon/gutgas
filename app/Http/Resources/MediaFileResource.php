<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        $returnValue = [
            'id' => $this->id,
            'name' => $this->name,
            'extension' => $this->extension,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'url' => $this->url,
        ];
        foreach ($this->scaledItems as $key => $scaledItem) {
            $returnValue[$scaledItem->scale] = $scaledItem->url;
        }

        return $returnValue;
    }
}
