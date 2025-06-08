<?php

namespace App\Controllers;

use App\Models\MTenantModel;
use App\Models\ServiceModel;
use App\Models\BookingModel;

class TenantDashboard extends BaseController
{
    protected $tenantModel;
    protected $serviceModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->tenantModel = new MTenantModel();
        $this->serviceModel = new ServiceModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Display tenant dashboard with stats and quick links
     */
    public function index($tenantId)
    {
        // Get tenant details
        $tenant = $this->tenantModel->find($tenantId);
        if (!$tenant) {
            return redirect()->to('/')->with('error', 'Tenant not found');
        }

        // Get tenant stats
        $stats = $this->getStats($tenantId);

        return view('tenant_website/dashboard', [
            'tenant' => $tenant,
            'stats' => $stats
        ]);
    }

    /**
     * Get tenant statistics
     */
    private function getStats($tenantId)
    {
        return [
            'total_services' => $this->serviceModel->where('intTenantID', $tenantId)->countAllResults(),
            'active_services' => $this->serviceModel->where('intTenantID', $tenantId)->where('bitActive', 1)->countAllResults(),
            'total_bookings' => $this->bookingModel->where('intTenantID', $tenantId)->countAllResults(),
            'pending_bookings' => $this->bookingModel->where('intTenantID', $tenantId)->where('txtStatus', 'pending')->countAllResults()
        ];
    }
}
