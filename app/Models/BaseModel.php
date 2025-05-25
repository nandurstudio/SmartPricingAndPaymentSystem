<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;
use App\Models\Traits\TenantTrait;

class BaseModel extends Model
{
    use AuditTrait, TenantTrait;

    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    
    protected $beforeInsert = ['addAuditFields'];
    protected $beforeUpdate = ['updateAuditFields'];

    protected function addAuditFields(array $data)
    {
        $session = session();
        $user = $session->get('user');
        
        $data['data']['guid'] = service('uuid')->uuid4()->toString();
        $data['data']['created_date'] = date('Y-m-d H:i:s');
        $data['data']['created_by'] = $user['username'] ?? 'system';
        $data['data']['is_active'] = $data['data']['is_active'] ?? true;
        
        return $data;
    }

    protected function updateAuditFields(array $data)
    {
        $session = session();
        $user = $session->get('user');
        
        $data['data']['updated_date'] = date('Y-m-d H:i:s');
        $data['data']['updated_by'] = $user['username'] ?? 'system';
        
        return $data;
    }

    public function findByGuid(string $guid)
    {
        return $this->where('guid', $guid)->first();
    }

    public function findActiveByTenant(int $tenantId)
    {
        return $this->where([
            'tenant_id' => $tenantId,
            'is_active' => true
        ])->findAll();
    }

    public function softDelete(int $id)
    {
        return $this->update($id, ['is_active' => false]);
    }

    // Fixed method signature to match parent class
    protected function doInsert(?array $data = null): bool
    {
        // Only add tenant_id if the field exists in the table
        if (in_array('tenant_id', $this->allowedFields) && session()->has('tenant')) {
            $this->tempData['tenant_id'] = session()->get('tenant')['id'] ?? null;
        }

        return parent::doInsert($data);
    }

    // Override base builder to apply tenant scope
    public function builder(?string $table = null)
    {
        $builder = parent::builder($table);
        
        return $this->applyTenantScope($builder);
    }
}
