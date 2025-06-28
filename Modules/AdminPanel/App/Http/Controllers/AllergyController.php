<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AdminPanel\App\Services\AllergyService;
use Modules\PatientManagement\App\Http\Requests\AllergyRequest;

class AllergyController extends Controller
{
    protected $allergyService;

    public function __construct(AllergyService $allergyService)
    {
        $this->allergyService = $allergyService;
    }

    public function index()
    {
        $allergies = $this->allergyService->index();
        return view('adminDashboard.allergies.index', compact('allergies'));
    }

    public function store(AllergyRequest $request)
    {
        $this->allergyService->store($request->validated());
        return back()->with('success', 'Allergy created successfully.');
    }

    public function update(AllergyRequest $request, $id)
    {
        $this->allergyService->update($id, $request->validated());
        return back()->with('success', 'Allergy updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->allergyService->destroy($id);
            return back()->with('success', 'Allergy deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete this Allergy. It may be associated with doctors.');
        }
    }
}
