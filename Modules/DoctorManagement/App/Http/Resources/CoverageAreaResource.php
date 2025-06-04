<?php

namespace Modules\DoctorManagement\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoverageAreaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            ...parent::toArray($request),
            'name' => $this->name,
        ];
    }
}
