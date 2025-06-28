<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AdminPanel\App\Services\ChronicConditionService;
use Modules\PatientManagement\App\Http\Requests\ChronicConditionRequest;

class ChronicConditionController extends Controller
{
    protected $chronicConditionService;

    public function __construct(ChronicConditionService $chronicConditionService)
    {
        $this->chronicConditionService = $chronicConditionService;
    }

    public function index()
    {
        $chronicConditions = $this->chronicConditionService->index();
        return view('adminDashboard.chronicCondition.index', compact('chronicConditions'));
    }

    public function store(ChronicConditionRequest $request)
    {
        $this->chronicConditionService->store($request->validated());
        return back()->with('success', 'Chronic Condition created successfully.');
    }

    public function update(ChronicConditionRequest $request, $id)
    {
        $this->chronicConditionService->update($id, $request->validated());
        return back()->with('success', 'Chronic Condition updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->chronicConditionService->destroy($id);
            return back()->with('success', 'Chronic Condition deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete this Chronic Condition. It may be associated with doctors.');
        }
    }
}
