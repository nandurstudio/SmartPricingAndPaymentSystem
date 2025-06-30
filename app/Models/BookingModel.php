<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'tr_bookings';
    protected $primaryKey = 'intBookingID';
    protected $allowedFields = [
        'txtBookingCode',
        'intServiceID',
        'intCustomerID',
        'intTenantID',
        'dtmBookingDate',
        'dtmStartTime',
        'dtmEndTime',
        'decPrice',
        'txtStatus',
        'txtPaymentStatus',
        'txtPaymentID',
        'txtGUID',
        'dtmCancelledDate',
        'txtCancelledReason',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'bitActive'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate';
    protected $updatedField = 'dtmUpdatedDate';

    // Get customer's bookings with service details
    public function getCustomerBookings($customerId)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, s.txtName as txtServiceName, t.txtTenantName as txtTenantName, u.txtFullName as txtCustomerName')
            ->join('m_services s', 'b.intServiceID = s.intServiceID', 'left')
            ->join('m_tenants t', 'b.intTenantID = t.intTenantID', 'left')
            ->join('m_user u', 'b.intCustomerID = u.intUserID', 'left')
            ->where('b.intCustomerID', $customerId)
            ->orderBy('b.dtmBookingDate DESC, b.dtmStartTime ASC')
            ->get()
            ->getResultArray();
    }

    // Get tenant's bookings
    public function getTenantBookings($tenantId)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, s.txtName as txtServiceName, u.txtFullName as txtCustomerName, u.txtEmail as txtCustomerEmail')
            ->join('m_services s', 'b.intServiceID = s.intServiceID', 'left')
            ->join('m_user u', 'b.intCustomerID = u.intUserID', 'left')
            ->where('b.intTenantID', $tenantId)
            ->orderBy('b.dtmBookingDate DESC, b.dtmStartTime ASC')
            ->get()
            ->getResultArray();
    }

    // Get booking details with all related info
    public function getBookingDetails($id)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, s.txtName as txtServiceName, s.txtDescription as txtServiceDescription, 
                     t.txtTenantName as txtTenantName,
                     u.txtFullName as txtCustomerName, u.txtEmail as txtCustomerEmail')
            ->join('m_services s', 'b.intServiceID = s.intServiceID', 'left')
            ->join('m_tenants t', 'b.intTenantID = t.intTenantID', 'left')
            ->join('m_user u', 'b.intCustomerID = u.intUserID', 'left')
            ->where('b.intBookingID', $id)
            ->get()
            ->getRowArray();
    }

    // Get bookings by date range for a service
    public function getBookingsByDateRange($serviceId, $startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->where('intServiceID', $serviceId)
            ->where('dtmBookingDate >=', $startDate)
            ->where('dtmBookingDate <=', $endDate)
            ->where('txtStatus !=', 'cancelled')
            ->orderBy('dtmBookingDate ASC, dtmStartTime ASC')
            ->get()
            ->getResultArray();
    }

    // Check if a slot is available
    public function isSlotAvailable($serviceId, $date, $startTime, $endTime)
    {
        $count = $this->db->table($this->table)
            ->where('intServiceID', $serviceId)
            ->where('dtmBookingDate', $date)
            ->where('txtStatus !=', 'cancelled')
            ->groupStart()
                ->where("(dtmStartTime < '$endTime' AND dtmEndTime > '$startTime')")
            ->groupEnd()
            ->countAllResults();

        return $count === 0;
    }

    // Check if booking can be cancelled
    public function canCancel($id)
    {
        $booking = $this->find($id);
        if (!$booking) {
            return false;
        }

        // If booking is already cancelled
        if ($booking['txtStatus'] === 'cancelled') {
            return false;
        }

        // Get cancellation policy from the tenant
        // In a real app, we would get this from tenant settings
        $hoursBeforeBooking = 24; // Default: cancel at least 24 hours before
        
        // Calculate time until booking
        $bookingDateTime = strtotime($booking['dtmBookingDate'] . ' ' . $booking['dtmStartTime']);
        $currentTime = time();
        $hoursUntilBooking = ($bookingDateTime - $currentTime) / 3600;
        
        return $hoursUntilBooking >= $hoursBeforeBooking;
    }

    // Get all bookings for a specific date and service
    public function getBookingsForDate($serviceId, $date)
    {
        return $this->db->table($this->table)
            ->select('dtmStartTime, dtmEndTime')
            ->where('intServiceID', $serviceId)
            ->where('dtmBookingDate', $date)
            ->where('txtStatus !=', 'cancelled')
            ->orderBy('dtmStartTime ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get recent bookings for a tenant
     * 
     * @param int $tenantId The tenant ID
     * @param int $limit Number of bookings to return (default: 5)
     * @return array Array of recent bookings with customer and service details
     */    public function getRecentBookings($tenantId, $limit = 5)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, 
                     s.txtName as txtServiceName, 
                     t.txtTenantName,
                     u.txtFullName as customer_name,
                     u.txtEmail as customer_email')
            ->join('m_services s', 'b.intServiceID = s.intServiceID', 'left')
            ->join('m_tenants t', 'b.intTenantID = t.intTenantID', 'left')
            ->join('m_user u', 'b.intCustomerID = u.intUserID', 'left')
            ->where('b.intTenantID', $tenantId)
            ->where('b.bitActive', 1)
            ->orderBy('b.dtmBookingDate DESC, b.dtmStartTime DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
