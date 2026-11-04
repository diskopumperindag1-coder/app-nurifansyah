<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'group' => $this->group,
            'key' => $this->key,
            'value' => match ($this->type) {
                'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
                'json' => json_decode($this->value, true),
                default => $this->value,
            },
            'type' => $this->type,
        ];
    }
}
