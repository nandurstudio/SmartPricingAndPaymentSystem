<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'tr_bookings'; // Tabel transaksi booking
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_code',
        'service_id',
        'customer_id',
        'tenant_id',
        'booking_date',
        'start_time',
        'end_time',
        'price',
        'status',
        'payment_status',
        'payment_id',
        'notes',
        'created_by',
        'created_date',
        'updated_by',
        'updated_date',
        'cancelled_date',
        'cancelled_reason'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_date';
    protected $updatedField = 'updated_date';

    // Get customer's bookings with service details
    public function getCustomerBookings($customerId)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, s.name as service_name, t.name as tenant_name, u.txtFullName as customer_name')
            ->join('m_services s', 'b.service_id = s.id', 'left')
            ->join('m_tenants t', 'b.tenant_id = t.id', 'left')
            ->join('m_user u', 'b.customer_id = u.intUserID', 'left')
            ->where('b.customer_id', $customerId)
            ->orderBy('b.booking_date DESC, b.start_time ASC')
            ->get()
            ->getResultArray();
    }

    // Get tenant's bookings
    public function getTenantBookings($tenantId)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, s.name as service_name, u.txtFullName as customer_name, u.txtEmail as customer_email')
            ->join('m_services s', 'b.service_id = s.id', 'left')
            ->join('m_user u', 'b.customer_id = u.intUserID', 'left')
            ->where('b.tenant_id', $tenantId)
            ->orderBy('b.booking_date DESC, b.start_time ASC')
            ->get()
            ->getResultArray();
    }

    // Get booking details with all related info
    public function getBookingDetails($id)
    {
        return $this->db->table($this->table . ' b')
            ->select('b.*, s.name as service_name, s.description as service_description, 
                     t.name as tenant_name, t.contact_email as tenant_email, t.contact_phone as tenant_phone,
                     u.txtFullName as customer_name, u.txtEmail as customer_email')
            ->join('m_services s', 'b.service_id = s.id', 'left')
            ->join('m_tenants t', 'b.tenant_id = t.id', 'left')
            ->join('m_user u', 'b.customer_id = u.intUserID', 'left')
            ->where('b.id', $id)
            ->get()
            ->getRowArray();
    }

    // Get bookings by date range for a service
    public function getBookingsByDateRange($serviceId, $startDate, $endDate)
    {
        return $this->db->table($this->table)
            ->where('service_id', $serviceId)
            ->where('booking_date >=', $startDate)
            ->where('booking_date <=', $endDate)
            ->where('status !=', 'cancelled')
            ->orderBy('booking_date ASC, start_time ASC')
            ->get()
            ->getResultArray();
    }

    // Check if a slot is available
    public function isSlotAvailable($serviceId, $date, $startTime, $endTime)
    {
        $count = $this->db->table($this->table)
            ->where('service_id', $serviceId)
            ->where('booking_date', $date)
            ->where('status !=', 'cancelled')
            ->groupStart()
                ->where("(start_time < '$endTime' AND end_time > '$startTime')")
            ->groupEnd()
            ->countAllResults();

        return $count === 0;
    }

    // Check if booking can be cancelled (e.g., not too close to booking time)
    public function canCancel($id)
    {
        $booking = $this->find($id);
        if (!$booking) {
            return false;
        }

        // If booking is already cancelled
        if ($booking['status'] === 'cancelled') {
            return false;
        }

        // Get cancellation policy from the tenant
        // In a real app, we would get this from tenant settings
        $hoursBeforeBooking = 24; // Default: cancel at least 24 hours before
        
        // Calculate time until booking
        $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
        $currentTime = time();
        $hoursUntilBooking = ($bookingDateTime - $currentTime) / 3600;
        
        return $hoursUntilBooking >= $hoursBeforeBooking;
    }
}
