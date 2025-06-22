<?php

namespace App\Models;

use CodeIgniter\Model;

class MTenantModel extends Model
{
    protected $table = 'm_tenants';
    protected $primaryKey = 'intTenantID';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'txtGUID',
        'txtTenantName',
        'txtSlug',
        'txtDomain',
        'txtTenantCode',
        'intServiceTypeID',
        'intOwnerID',
        'txtSubscriptionPlan',
        'txtSubscriptionStatus',
        'dtmSubscriptionStartDate',
        'dtmSubscriptionEndDate',
        'dtmTrialEndsAt',
        'jsonSettings',
        'jsonPaymentSettings',
        'txtMidtransClientKey',
        'txtMidtransServerKey',
        'txtLogo',
        'txtTheme',
        'txtStatus',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate';
    protected $updatedField = 'dtmUpdatedDate';
    
    protected $dateFormat = 'datetime';    protected $validationRules = [
        'txtTenantName' => 'required|min_length[3]|max_length[255]',
        'intServiceTypeID' => 'required|numeric',
        'intOwnerID' => 'required|numeric',        'txtSlug' => 'permit_empty|max_length[255]',
        'txtDomain' => 'permit_empty|alpha_dash|max_length[255]',
        'txtTenantCode' => 'permit_empty|max_length[50]',
        'txtSubscriptionPlan' => 'required|in_list[free,basic,premium,enterprise]',
        'txtStatus' => 'required|in_list[active,inactive,suspended,pending,pending_verification,pending_payment,payment_failed]',
        'txtTheme' => 'permit_empty|in_list[default,dark,light]',
        'bitActive' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get tenant with service type details
     */
    public function getWithServiceType(int $id = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, st.txtName as service_type_name, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_service_types st', 't.intServiceTypeID = st.intServiceTypeID', 'left')
            ->join('m_user u', 't.intOwnerID = u.intUserID', 'left');

        if ($id) {
            return $builder->where('t.intTenantID', $id)->get()->getRowArray();
        }

        return $builder->orderBy('t.dtmCreatedDate', 'DESC')->get()->getResultArray();
    }

    /**
     * Get tenant by slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('txtSlug', $slug)->first();
    }

    /**
     * Get tenant with owner details
     */
    public function getWithOwner(int $id = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_user u', 't.intOwnerID = u.intUserID', 'left');

        if ($id) {
            return $builder->where('t.intTenantID', $id)->get()->getRowArray();
        }

        return $builder->orderBy('t.dtmCreatedDate', 'DESC')->get()->getResultArray();
    }

    /**
     * Get tenants by service type
     */
    public function getByServiceType(int $serviceTypeId)
    {
        return $this->where('intServiceTypeID', $serviceTypeId)
                    ->where('bitActive', 1)
                    ->findAll();
    }

    /**
     * Search tenants
     */
    public function search(string $keyword)
    {
        return $this->like('txtTenantName', $keyword)
                    ->orLike('txtSlug', $keyword)
                    ->orLike('txtDomain', $keyword)
                    ->where('bitActive', 1)
                    ->findAll();
    }

    /**
     * Get popular service types
     */
    public function getPopularServiceTypes($limit = 5)
    {
        return $this->db->table($this->table . ' t')
                        ->select('st.txtName, st.txtIcon, COUNT(t.intTenantID) as tenant_count')
                        ->join('m_service_types st', 't.intServiceTypeID = st.intServiceTypeID')
                        ->where('t.bitActive', 1)
                        ->groupBy('st.intServiceTypeID, st.txtName, st.txtIcon')
                        ->orderBy('tenant_count', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->getResultArray();
    }

    /**
     * Activate subscription for tenant
     */
    public function activateSubscription($tenantId, $transactionData)
    {
        $plan = $this->find($tenantId)['txtSubscriptionPlan'];
        $duration = $this->getSubscriptionDuration($plan);
        
        $data = [
            'txtSubscriptionStatus' => 'active',
            'txtStatus' => 'active',
            'dtmSubscriptionStartDate' => date('Y-m-d H:i:s'),
            'dtmSubscriptionEndDate' => date('Y-m-d H:i:s', strtotime("+{$duration} months")),
            'jsonPaymentSettings' => json_encode([
                'currency' => 'IDR',
                'last_payment_id' => $transactionData['transaction_id'] ?? null,
                'last_payment_status' => $transactionData['transaction_status'] ?? null,
                'last_payment_date' => date('Y-m-d H:i:s')
            ])
        ];

        return $this->update($tenantId, $data);
    }

    /**
     * Update Midtrans keys
     */
    public function updateMidtransKeys($tenantId, $clientKey, $serverKey)
    {
        return $this->update($tenantId, [
            'txtMidtransClientKey' => $clientKey,
            'txtMidtransServerKey' => $serverKey
        ]);
    }

    /**
     * Generate tenant slug
     */
    public function generateTenantSlug($name)
    {
        $baseSlug = url_title($name, '-', true);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->where('txtSlug', $slug)->first() !== null) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get user tenants
     */
    public function getUserTenants($userId, $roleId)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, st.txtName as service_type_name, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_service_types st', 't.intServiceTypeID = st.intServiceTypeID', 'left')
            ->join('m_user u', 't.intOwnerID = u.intUserID', 'left');

        if ($roleId == 1) { // Admin sees all tenants
            return $builder->get()->getResultArray();
        }
        
        // Regular users see only their own tenants
        return $builder->where('t.intOwnerID', $userId)
                      ->where('t.bitActive', 1)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Check if subdomain is available
     */
    public function isSubdomainAvailable(string $subdomain)
    {
        if (empty($subdomain)) {
            return false;
        }

        $existingTenant = $this->where('txtDomain', $subdomain)
                              ->first();
        
        return !$existingTenant;
    }

    /**
     * Normalize subdomain string
     */    public function normalizeSubdomain($subdomain)
    {
        if (empty($subdomain)) {
            return '';
        }

        // Remove protocol, www, and any domain parts
        $subdomain = preg_replace('#^https?://#', '', $subdomain);
        $subdomain = preg_replace('#^www\.#', '', $subdomain);
        
        // Get only the first part before any dots
        $subdomain = explode('.', $subdomain)[0];
        
        // Convert to lowercase
        $subdomain = strtolower($subdomain);
        
        // Replace spaces and special chars with hyphens
        $subdomain = preg_replace('/[^a-z0-9\-]/', '-', $subdomain);
        
        // Remove multiple consecutive hyphens
        $subdomain = preg_replace('/-+/', '-', $subdomain);
        
        // Remove leading and trailing hyphens
        $subdomain = trim($subdomain, '-');
        
        // Ensure we have a valid subdomain
        if (empty($subdomain) || !preg_match('/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/', $subdomain)) {
            return '';
        }

        return $subdomain;
    }

    /**
     * Get subscription duration
     */
    private function getSubscriptionDuration($plan)
    {
        $durations = [
            'free' => 1,
            'basic' => 1,
            'premium' => 3,
            'enterprise' => 12
        ];

        return $durations[$plan] ?? 1;
    }

    /**
     * Generate default CSS for a tenant
     */
    public function generateDefaultCSS($tenantId)
    {
        $tenant = $this->find($tenantId);
        if (!$tenant) {
            return false;
        }

        $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
        $primaryColor = $settings['primaryColor'] ?? '#6366f1';
        $theme = $settings['theme'] ?? 'light';

        // Create CSS content
        $css = "/* Auto-generated CSS for tenant {$tenant['txtTenantName']} */\n\n";
        $css .= ":root {\n";
        $css .= "    --tenant-primary: {$primaryColor};\n";
        $css .= "}\n\n";

        // Add theme-specific overrides
        switch ($theme) {
            case 'dark':
                $css .= ".theme-dark .navbar-brand,\n";
                $css .= ".theme-dark .nav-link {\n";
                $css .= "    color: #f8f9fa;\n";
                $css .= "}\n\n";
                $css .= ".theme-dark .card {\n";
                $css .= "    background-color: #1e293b;\n";
                $css .= "    border-color: rgba(255,255,255,0.1);\n";
                $css .= "}\n";
                break;
            
            case 'light':
            default:
                $css .= ".theme-light .navbar-brand,\n";
                $css .= ".theme-light .nav-link {\n";
                $css .= "    color: #1e293b;\n";
                $css .= "}\n\n";
                $css .= ".theme-light .card {\n";
                $css .= "    background-color: #ffffff;\n";
                $css .= "    border-color: rgba(0,0,0,0.1);\n";
                $css .= "}\n";
                break;
        }

        // Add custom button styles
        $css .= "\n/* Custom Button Styles */\n";
        $css .= ".btn-primary {\n";
        $css .= "    background-color: var(--tenant-primary);\n";
        $css .= "    border-color: var(--tenant-primary);\n";
        $css .= "}\n\n";
        $css .= ".btn-primary:hover {\n";
        $css .= "    background-color: " . $this->adjustBrightness($primaryColor, -10) . ";\n";
        $css .= "    border-color: " . $this->adjustBrightness($primaryColor, -10) . ";\n";
        $css .= "}\n";

        // Ensure the directory exists
        $cssDir = FCPATH . 'uploads/tenants/css';
        if (!is_dir($cssDir)) {
            mkdir($cssDir, 0777, true);
        }

        // Write the CSS file
        $cssPath = $cssDir . '/' . $tenantId . '_custom.css';
        return file_put_contents($cssPath, $css) !== false;
    }

    /**
     * Adjust color brightness
     * @param string $hex Hex color code
     * @param int $steps Steps to adjust (-255 to 255)
     * @return string Adjusted hex color
     */
    private function adjustBrightness($hex, $steps)
    {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Adjust
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        // Convert back to hex
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
    
    /**
     * Update custom CSS for a tenant
     */
    public function updateCustomCSS($tenantId, $customCSS = '')
    {
        $cssDir = FCPATH . 'uploads/tenants/css';
        if (!is_dir($cssDir)) {
            mkdir($cssDir, 0777, true);
        }

        $cssFile = $cssDir . '/' . $tenantId . '_custom.css';
        return file_put_contents($cssFile, $customCSS) !== false;
    }

    /**
     * Get custom CSS for a tenant
     */
    protected function getCustomCSS($tenant)
    {
        $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
        $colors = $settings['colors'] ?? [];

        $css = [];
        
        // Primary color
        if (!empty($colors['primary'])) {
            $css[] = ":root { --bs-primary: {$colors['primary']}; }";
        }

        // Custom styles based on tenant settings
        if (!empty($settings['customCSS'])) {
            $css[] = $settings['customCSS'];
        }

        return implode("\n\n", $css);
    }

    /**
     * Get tenant details with service type information and owner details
     */
    public function getTenantDetails(int $id)
    {
        return $this->db->table($this->table . ' t')
            ->select('t.*, st.txtName as service_type_name, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_service_types st', 't.intServiceTypeID = st.intServiceTypeID', 'left')
            ->join('m_user u', 't.intOwnerID = u.intUserID', 'left')
            ->where('t.intTenantID', $id)
            ->get()
            ->getRowArray();
    }
}