<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ClassesResource;


class ClassesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            //'data' => $this->collection,
            'data' => ClassesResource::collection($this->collection),
            'links' => [
                'self' => 'link-value',
            ]
        ];
    }
}
