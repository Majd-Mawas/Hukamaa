<?php

namespace Modules\DoctorManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CoverageAreaResource extends ApiResource
{
    public function toArray($request): array
    {
        return [
            ...parent::toArray($request),
            'name' => $this->name,
        ];
    }
}
