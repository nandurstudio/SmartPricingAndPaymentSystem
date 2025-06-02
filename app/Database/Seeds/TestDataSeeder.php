<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Add test tenant
        $tenantID = $this->db->table('m_tenants')->insert([
            'txtTenantName' => 'Test Tenant',
            'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
            'intServiceTypeID' => 1, // Futsal Court
            'intOwnerID' => 1, // Admin user            'txtDomain' => 'test.booking.com',
            'txtSlug' => 'test-tenant',
            'txtSubscriptionPlan' => 'basic',
            'txtStatus' => 'active',
            'bitActive' => 1,
            'txtCreatedBy' => 'system',
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ], true);

        // Add test services
        $this->db->table('m_services')->insertBatch([
            [                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intTenantID' => $tenantID,
                'intServiceTypeID' => 1,
                'txtName' => 'Futsal Field A',
                'txtDescription' => 'Main futsal field with synthetic grass',
                'decPrice' => 150000,
                'intDuration' => 60,
                'intCapacity' => 12,
                'txtImage' => 'futsal-a.jpg',
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intTenantID' => $tenantID,
                'intServiceTypeID' => 1,
                'txtName' => 'Futsal Field B',
                'txtDescription' => 'Secondary futsal field with rubber surface',
                'decPrice' => 120000,
                'intDuration' => 60,
                'intCapacity' => 12,
                'txtImage' => 'futsal-b.jpg',
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]
        ]);

        // Get service IDs
        $services = $this->db->table('m_services')
            ->where('intTenantID', $tenantID)
            ->get()->getResult();

        // Add test schedules
        foreach ($services as $service) {
            $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($weekDays as $day) {
                $this->db->table('m_schedules')->insert([
                    'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                    'intServiceID' => $service->intServiceID,
                    'txtDay' => $day,
                    'dtmStartTime' => '08:00:00',
                    'dtmEndTime' => '22:00:00',
                    'intSlotDuration' => 60,
                    'bitIsAvailable' => 1,
                    'bitActive' => 1,
                    'txtCreatedBy' => 'system',
                    'dtmCreatedDate' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // Add special schedule (holiday)
        $this->db->table('m_special_schedules')->insert([
            'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
            'intServiceID' => $services[0]->intServiceID,
            'dtmSpecialDate' => '2025-12-25',
            'bitIsClosed' => 1,
            'txtNote' => 'Closed for Christmas',
            'bitActive' => 1,
            'txtCreatedBy' => 'system',
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ]);

        // Add test booking
        $bookingID = $this->db->table('tr_bookings')->insert([
            'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
            'txtBookingCode' => 'BK' . date('YmdHis'),
            'intServiceID' => $services[0]->intServiceID,
            'intCustomerID' => 1,
            'intTenantID' => $tenantID,
            'dtmBookingDate' => '2025-06-03',
            'dtmStartTime' => '10:00:00',
            'dtmEndTime' => '11:00:00',
            'decPrice' => 150000,
            'txtStatus' => 'confirmed',
            'txtPaymentStatus' => 'paid',
            'bitActive' => 1,
            'txtCreatedBy' => 'system',
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ], true);

        // Add test payment
        $this->db->table('tr_payments')->insert([
            'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
            'intBookingID' => $bookingID,
            'txtPaymentCode' => 'PAY' . date('YmdHis'),
            'decAmount' => 150000,
            'txtPaymentMethod' => 'bank_transfer',
            'dtmPaymentDate' => date('Y-m-d H:i:s'),
            'txtStatus' => 'success',
            'txtTransactionID' => 'TRX' . date('YmdHis'),
            'jsonPaymentDetails' => json_encode([
                'bank' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'Test Account'
            ]),
            'bitActive' => 1,
            'txtCreatedBy' => 'system',
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ]);
    }
}
