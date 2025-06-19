<?php

namespace Modules\DoctorManagement\App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\DoctorManagement\App\Models\CoverageArea;

class CoverageAreaService
{
    /**
     * Get all coverage areas with pagination.
     */
    public function index(): LengthAwarePaginator
    {
        return CoverageArea::latest()->paginate(10);
    }

    /**
     * Create a new coverage area.
     */
    public function store(array $data): CoverageArea
    {
        return CoverageArea::create($data);
    }

    /**
     * Get a specific coverage area.
     */
    public function show(int $id): CoverageArea
    {
        return CoverageArea::findOrFail($id);
    }

    /**
     * Update a coverage area.
     */
    public function update(int $id, array $data): CoverageArea
    {
        $coverageArea = CoverageArea::findOrFail($id);
        $coverageArea->update($data);
        return $coverageArea;
    }

    /**
     * Delete a coverage area.
     */
    public function destroy(int $id): bool
    {
        $coverageArea = CoverageArea::findOrFail($id);
        return $coverageArea->delete();
    }
}
