<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;

class ClassesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     /* public $status;
     public $message;
     public $resource;

     public function __construct($status, $message, $resource){
         parent::__construct($resource);
         $this->status  = $status;
         $this->message = $message;
     } */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'capacity' => $this->capacity,
            'location' => $this->location,
            'instructor_id' => $this->instructor_id,
            'type_id' => $this->type_id,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'status' => $this->status

        ];
    }
}
