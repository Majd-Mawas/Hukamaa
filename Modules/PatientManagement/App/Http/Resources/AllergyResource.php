<?php

namespace Modules\PatientManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class AllergyResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'name' => $this->name,
        ];
    }
}
