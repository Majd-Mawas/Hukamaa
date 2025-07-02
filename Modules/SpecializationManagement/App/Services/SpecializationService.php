<?php

namespace Modules\SpecializationManagement\App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\SpecializationManagement\App\Models\Specialization;

class SpecializationService
{
    /**
     * Get all specializations with pagination.
     */
    public function index(): LengthAwarePaginator
    {
        return Specialization::latest()->get();
    }

    /**
     * Create a new specialization.
     */
    public function store(array $data): Specialization
    {
        return Specialization::create($data);
    }

    /**
     * Get a specific specialization.
     */
    public function show(int $id): Specialization
    {
        return Specialization::findOrFail($id);
    }

    /**
     * Update a specialization.
     */
    public function update(int $id, array $data): Specialization
    {
        $specialization = Specialization::findOrFail($id);
        $specialization->update($data);
        return $specialization;
    }

    /**
     * Delete a specialization.
     */
    public function destroy(int $id): bool
    {
        $specialization = Specialization::findOrFail($id);
        return $specialization->delete();
    }
}
