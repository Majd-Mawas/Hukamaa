<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\DoctorManagement\App\Http\Requests\CoverageAreaRequest;
use Modules\DoctorManagement\App\Models\CoverageArea;
use Modules\DoctorManagement\App\Services\CoverageAreaService;

class CoverageAreaController extends Controller
{
    protected $coverageAreaService;

    public function __construct(CoverageAreaService $coverageAreaService)
    {
        $this->coverageAreaService = $coverageAreaService;
    }

    public function index()
    {
        $coverageAreas = $this->coverageAreaService->index();
        return view('doctorDashboard.coverageAreas.index', compact('coverageAreas'));
    }

    public function store(CoverageAreaRequest $request)
    {
        $this->coverageAreaService->store($request->validated());
        return back()->with('success', 'Coverage area created successfully.');
    }

    public function update(CoverageAreaRequest $request, $id)
    {
        $this->coverageAreaService->update($id, $request->validated());
        return back()->with('success', 'Coverage area updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->coverageAreaService->destroy($id);
            return back()->with('success', 'Coverage area deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete this coverage area. It may be associated with doctors.');
        }
    }
}
