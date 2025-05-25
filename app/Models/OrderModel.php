<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    // Update to use tr_orders instead of t_orders
    protected $table = 'tr_orders';
    protected $primaryKey = 'intOrderID';
    
    protected $allowedFields = [
        'intUserID', 'txtOrderStatus', 'txtPaymentStatus',
        'dtmOrderDate', 'txtCreatedBy', 'dtmCreatedDate',
        'txtLastUpdatedBy', 'dtmLastUpdatedDate', 'txtGUID', 'tenant_id'
    ];

    protected $useTimestamps = false;

    public function getOrdersByUser($userId, $limit = null)
    {
        $builder = $this->db->table($this->table)
                           ->where('intUserID', $userId)
                           ->orderBy('dtmOrderDate', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    public function getOrdersWithDetails($tenantId = null)
    {
        $builder = $this->db->table($this->table . ' o')
                           ->select('o.*, u.txtFullName as customer_name, u.txtEmail as customer_email')
                           ->join('m_user u', 'o.intUserID = u.intUserID')
                           ->orderBy('o.dtmOrderDate', 'DESC');

        if ($tenantId !== null) {
            $builder->where('o.tenant_id', $tenantId);
        }

        return $builder->get()->getResultArray();
    }

    public function getOrderDetails($orderId)
    {
        return $this->db->table($this->table . ' o')
                        ->select('o.*, u.txtFullName as customer_name, u.txtEmail as customer_email')
                        ->join('m_user u', 'o.intUserID = u.intUserID')
                        ->where('o.intOrderID', $orderId)
                        ->get()
                        ->getRowArray();
    }

    public function getOrderTransactions($orderId)
    {
        return $this->db->table('tr_transaction')
                        ->where('intOrderID', $orderId)
                        ->get()
                        ->getResultArray();
    }

    public function searchOrders($searchTerm, $tenantId = null)
    {
        $builder = $this->db->table($this->table . ' o')
                           ->select('o.*, u.txtFullName as customer_name, u.txtEmail as customer_email')
                           ->join('m_user u', 'o.intUserID = u.intUserID')
                           ->groupStart()
                                ->like('u.txtFullName', $searchTerm)
                                ->orLike('u.txtEmail', $searchTerm)
                                ->orLike('o.txtOrderStatus', $searchTerm)
                                ->orLike('o.txtPaymentStatus', $searchTerm)
                                ->orLike('o.intOrderID', $searchTerm)
                           ->groupEnd()
                           ->orderBy('o.dtmOrderDate', 'DESC');

        if ($tenantId !== null) {
            $builder->where('o.tenant_id', $tenantId);
        }

        return $builder->get()->getResultArray();
    }
}
