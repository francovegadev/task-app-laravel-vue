<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TaskResource extends JsonResource
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
            'description' => $this->description,
            'image' => [
               'path' => $this->when(true, fn() => $this->resource->getTaskImageAttribute()),
               'url' => $this->image ? $this->image->url() : asset('defaults/taskDefault.jpg')
            ],
            // 'image' => ImageResource::collection($this->images),
            'due_date' => Carbon::parse($this->due_date)->format('d-m-Y'),
            'status' => $this->status,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
