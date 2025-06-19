<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SpecializationManagement\App\Http\Requests\SpecializationRequest;
use Modules\SpecializationManagement\App\Models\Specialization;
use Modules\SpecializationManagement\App\Services\SpecializationService;

class SpecializationController extends Controller
{
    protected $specializationService;

    public function __construct(SpecializationService $specializationService)
    {
        $this->specializationService = $specializationService;
    }

    public function index()
    {
        $specializations = $this->specializationService->index();
        return view('adminDashboard.specializations.index', compact('specializations'));
    }

    public function store(SpecializationRequest $request)
    {
        $this->specializationService->store($request->validated());
        return back()->with('success', 'Specialization created successfully.');
    }

    public function update(SpecializationRequest $request, $id)
    {
        $this->specializationService->update($id, $request->validated());
        return back()->with('success', 'Specialization updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->specializationService->destroy($id);
            return back()->with('success', 'Specialization deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete this specialization. It may be associated with doctors.');
        }
    }
}
