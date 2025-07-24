<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware('auth');
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = $this->dashboardService->getDashboardStats();
        return view('admin.index', compact('stats'));
    }
}
