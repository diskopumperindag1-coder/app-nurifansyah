<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
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
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->when($request->routeIs('*.show') || ! $request->is('*/posts'), $this->content),
            'image_url' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'is_featured' => (bool) $this->is_featured,
            'view_count' => (int) $this->view_count,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatar' => $this->user?->avatar ? Storage::disk('public')->url($this->user->avatar) : null,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
