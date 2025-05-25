<?php

namespace App\Models\Traits;

trait TenantTrait
{
    protected function applyTenantScope($builder)
    {
        // Only apply tenant scope if user is logged in and not an admin
        if (session()->has('isLoggedIn') && session()->get('roleID') != 1) {
            // Get tenant_id from session
            $tenantId = session()->get('tenant')['id'] ?? null;

            if ($tenantId) {
                $builder->where("{$this->table}.tenant_id", $tenantId);
            }
        }

        return $builder;
    }

    public function find($id = null)
    {
        $builder = $this->builder();
        $this->applyTenantScope($builder);
        
        return parent::find($id);
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();
        $this->applyTenantScope($builder);
        
        return parent::findAll($limit, $offset);
    }

    public function first()
    {
        $builder = $this->builder();
        $this->applyTenantScope($builder);
        
        return parent::first();
    }
}
