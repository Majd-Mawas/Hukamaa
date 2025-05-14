<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{
    protected function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function error($message = 'Error', $status = 400, $data = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function getMetaData($data): ?array
    {
        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            return [
                'current_page' => $data->resource->currentPage(),
                'last_page' => $data->resource->lastPage(),
                'per_page' => $data->resource->perPage(),
                'total' => $data->resource->total(),
            ];
        }

        return null;
    }
}
