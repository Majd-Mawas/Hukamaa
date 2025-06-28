<?php

namespace Modules\AdminPanel\App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\PatientManagement\App\Models\Allergy;

class AllergyService
{

    public function index(): LengthAwarePaginator
    {
        return Allergy::latest()->paginate(10);
    }


    public function store(array $data): Allergy
    {
        return Allergy::create($data);
    }

    public function show(int $id): Allergy
    {
        return Allergy::findOrFail($id);
    }


    public function update(int $id, array $data): Allergy
    {
        $Allergy = Allergy::findOrFail($id);
        $Allergy->update($data);
        return $Allergy;
    }

    public function destroy(int $id): bool
    {
        $Allergy = Allergy::findOrFail($id);
        return $Allergy->delete();
    }
}
