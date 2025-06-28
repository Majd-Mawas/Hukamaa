<?php

namespace Modules\AdminPanel\App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\PatientManagement\App\Models\ChronicCondition;

class ChronicConditionService
{
    public function index(): LengthAwarePaginator
    {
        return chronicCondition::latest()->paginate(10);
    }

    public function store(array $data): ChronicCondition
    {
        return chronicCondition::create($data);
    }

    public function show(int $id): chronicCondition
    {
        return chronicCondition::findOrFail($id);
    }

    public function update(int $id, array $data): chronicCondition
    {
        $chronicCondition = chronicCondition::findOrFail($id);
        $chronicCondition->update($data);
        return $chronicCondition;
    }

    public function destroy(int $id): bool
    {
        $chronicCondition = chronicCondition::findOrFail($id);
        return $chronicCondition->delete();
    }
}
