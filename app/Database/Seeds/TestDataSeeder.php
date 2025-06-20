<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        try {
            $this->db->transBegin();

            // Define users based on roles
            $users = [
                [
                    'txtEmail' => 'admin@example.com',
                    'txtUserName' => 'admin',
                    'txtPassword' => password_hash('admin123', PASSWORD_DEFAULT),
                    'txtFullName' => 'System Administrator',
                    'bitActive' => 1,
                    'intRoleID' => 1, // Super Admin
                    'dtmJoinDate' => date('Y-m-d H:i:s'),
                    'txtGUID' => 'admin_' . uniqid(),
                ],
                [
                    'txtEmail' => 'staff@example.com',
                    'txtUserName' => 'staff',
                    'txtPassword' => password_hash('staff123', PASSWORD_DEFAULT),
                    'txtFullName' => 'Staff User',
                    'bitActive' => 1,
                    'intRoleID' => 2, // Staff
                    'dtmJoinDate' => date('Y-m-d H:i:s'),
                    'txtGUID' => 'staff_' . uniqid(),
                ],
                [
                    'txtEmail' => 'tenant@example.com',
                    'txtUserName' => 'tenant',
                    'txtPassword' => password_hash('tenant123', PASSWORD_DEFAULT),
                    'txtFullName' => 'Tenant User',
                    'bitActive' => 1,
                    'intRoleID' => 3, // Tenant
                    'dtmJoinDate' => date('Y-m-d H:i:s'),
                    'txtGUID' => 'tenant_' . uniqid(),
                ],
                [
                    'txtEmail' => 'customer@example.com',
                    'txtUserName' => 'customer',
                    'txtPassword' => password_hash('customer123', PASSWORD_DEFAULT),
                    'txtFullName' => 'Customer User',
                    'bitActive' => 1,
                    'intRoleID' => 4, // Customer
                    'dtmJoinDate' => date('Y-m-d H:i:s'),
                    'txtGUID' => 'customer_' . uniqid(),
                ]
            ];

            // Clean up existing test data first
            $this->db->table('tr_payments')->where('txtCreatedBy', 'system')->delete();
            $this->db->table('tr_bookings')->where('txtCreatedBy', 'system')->delete();
            $this->db->table('m_special_schedules')->where('txtCreatedBy', 'system')->delete();
            $this->db->table('m_schedules')->where('txtCreatedBy', 'system')->delete();
            $this->db->table('m_services')->where('txtCreatedBy', 'system')->delete();
            $this->db->table('m_tenants')->where('txtSlug', 'test-tenant')->delete();
            $this->db->table('m_service_type_attributes')->where('txtCreatedBy', 'Seeder')->delete();
            $this->db->table('m_user')->whereIn('txtEmail', array_column($users, 'txtEmail'))->delete();

            // Seed users
            foreach ($users as $userData) {
                $userData['txtCreatedBy'] = 'Seeder';
                $userData['dtmCreatedDate'] = date('Y-m-d H:i:s');
                $userData['txtUpdatedBy'] = 'Seeder';
                $userData['dtmUpdatedDate'] = date('Y-m-d H:i:s');
                $this->db->table('m_user')->insert($userData);
            }

            // Get admin user ID
            $adminUser = $this->db->table('m_user')
                ->where('txtEmail', 'admin@example.com')
                ->get()
                ->getRow();

            if (!$adminUser) {
                throw new Exception('Admin user not found');
            }

            // Get or create service type
            $serviceTypeData = [
                'txtName' => 'Futsal Field',
                'txtSlug' => 'futsal-field',
                'txtDescription' => 'Indoor futsal field rental',
                'txtIcon' => 'bi-dribbble',
                'txtCategory' => 'Sports',
                'bitIsSystem' => 1,
                'bitIsApproved' => 1,
                'bitActive' => 1,
                'txtGUID' => 'st_' . uniqid(),
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ];

            $this->db->table('m_service_types')->insert($serviceTypeData);
            $serviceType = $this->db->table('m_service_types')
                ->where('txtSlug', $serviceTypeData['txtSlug'])
                ->get()
                ->getRow();

            if (!$serviceType) {
                throw new Exception('Service type not created');
            }

            // Add service type attributes
            $this->db->table('m_service_type_attributes')->insert([
                'intServiceTypeID' => $serviceType->intServiceTypeID,
                'txtName' => 'Field Size',
                'txtLabel' => 'Field Size',
                'bitRequired' => 1,
                'txtFieldType' => 'select',
                'jsonOptions' => json_encode(['Small', 'Medium', 'Large']),
                'bitActive' => 1,
                'txtGUID' => 'sta_' . uniqid(),
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);

            // Create tenant
            $tenantData = [
                'txtTenantName' => 'Test Tenant',
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $serviceType->intServiceTypeID,
                'intOwnerID' => $adminUser->intUserID,
                'txtDomain' => 'test.booking.com',
                'txtSlug' => 'test-tenant',
                'txtSubscriptionPlan' => 'basic',
                'txtStatus' => 'active',
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ];

            $this->db->table('m_tenants')->insert($tenantData);
            $tenant = $this->db->table('m_tenants')
                ->where('txtSlug', $tenantData['txtSlug'])
                ->get()
                ->getRow();

            if (!$tenant) {
                throw new Exception('Tenant not created');
            }

            // Add test services
            $servicesData = [
                [
                    'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                    'intTenantID' => $tenant->intTenantID,
                    'intServiceTypeID' => $serviceType->intServiceTypeID,
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
                [
                    'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                    'intTenantID' => $tenant->intTenantID,
                    'intServiceTypeID' => $serviceType->intServiceTypeID,
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
            ];

            $this->db->table('m_services')->insertBatch($servicesData);

            // Get services
            $services = $this->db->table('m_services')
                ->where('intTenantID', $tenant->intTenantID)
                ->get()
                ->getResult();

            if (empty($services)) {
                throw new Exception('Services not created');
            }

            // Add schedules for each service
            foreach ($services as $service) {
                $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $scheduleData = [];

                foreach ($weekDays as $day) {
                    $scheduleData[] = [
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
                    ];
                }

                $this->db->table('m_schedules')->insertBatch($scheduleData);
            }

            // Add special schedule
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
            $bookingData = [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtBookingCode' => 'BK' . date('YmdHis'),
                'intServiceID' => $services[0]->intServiceID,
                'intCustomerID' => $adminUser->intUserID,
                'intTenantID' => $tenant->intTenantID,
                'dtmBookingDate' => '2025-06-03',
                'dtmStartTime' => '10:00:00',
                'dtmEndTime' => '11:00:00',
                'decPrice' => 150000,
                'txtStatus' => 'confirmed',
                'txtPaymentStatus' => 'paid',
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ];

            $this->db->table('tr_bookings')->insert($bookingData);
            $booking = $this->db->table('tr_bookings')
                ->where('txtGUID', $bookingData['txtGUID'])
                ->get()
                ->getRow();

            if (!$booking) {
                throw new Exception('Booking not created');
            }

            // Add test payment
            $paymentData = [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intBookingID' => $booking->intBookingID,
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
            ];

            $this->db->table('tr_payments')->insert($paymentData);

            // Commit transaction if everything is OK
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception('Transaction failed');
            }

        } catch (Exception $e) {
            // Rollback transaction if any error occurs
            $this->db->transRollback();
            die('Error in TestDataSeeder: ' . $e->getMessage());
        }
    }
}
