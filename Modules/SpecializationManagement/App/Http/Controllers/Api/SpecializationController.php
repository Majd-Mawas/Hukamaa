<?php

namespace Modules\SpecializationManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasServiceActions;
use Modules\SpecializationManagement\App\Http\Resources\SpecializationResource;
use Modules\SpecializationManagement\App\Http\Requests\SpecializationRequest;
use Modules\SpecializationManagement\App\Services\SpecializationService;

class SpecializationController extends Controller
{
    use HasServiceActions;

    protected function getService(): object
    {
        return app(SpecializationService::class);
    }

    protected function getResourceClass(): string
    {
        return SpecializationResource::class;
    }

    protected function getRequestClass(): string
    {
        return SpecializationRequest::class;
    }
}
