<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\AdminPanel\App\Services\DashboardService;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = (object) $this->dashboardService->getDashboardStats();
        // return $stats;
        return view('doctorDashboard.index', compact('stats'));
    }
}
