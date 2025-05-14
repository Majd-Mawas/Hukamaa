<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasCrudActions
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $items = $this->getModelClass()::latest()->paginate(10);
        return $this->success($this->getResourceClass()::collection($items));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = app($this->getRequestClass())->validated();
        $item = $this->getModelClass()::create($validated);
        return $this->success(new ($this->getResourceClass())($item), 'Created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $item = $this->getModelClass()::findOrFail($id);
        return $this->success(new ($this->getResourceClass())($item));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $item = $this->getModelClass()::findOrFail($id);
        $validated = app($this->getRequestClass())->validated();
        $item->update($validated);
        return $this->success(new ($this->getResourceClass())($item), 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $item = $this->getModelClass()::findOrFail($id);
        $item->delete();
        return $this->success(null, 'Deleted successfully', 204);
    }

    // These methods must be implemented in the controller
    abstract protected function getModelClass(): string;
    abstract protected function getResourceClass(): string;
    abstract protected function getRequestClass(): string;
}
