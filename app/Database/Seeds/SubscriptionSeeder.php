<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        // Insert default subscription plans
        $plans = [
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Free Plan',
                'txtCode' => 'free',
                'decAmount' => 0.00,
                'intDuration' => 1,
                'jsonFeatures' => json_encode([
                    'max_services' => 5,
                    'max_staff' => 1,
                    'max_bookings_per_month' => 50
                ]),
                'txtDescription' => 'Basic features for small businesses'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Basic Plan',
                'txtCode' => 'basic',
                'decAmount' => 99000.00,
                'intDuration' => 1,
                'jsonFeatures' => json_encode([
                    'max_services' => 20,
                    'max_staff' => 5,
                    'max_bookings_per_month' => 200
                ]),
                'txtDescription' => 'Essential features for growing businesses'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Premium Plan',
                'txtCode' => 'premium',
                'decAmount' => 199000.00,
                'intDuration' => 3,
                'jsonFeatures' => json_encode([
                    'max_services' => 50,
                    'max_staff' => 15,
                    'max_bookings_per_month' => 500
                ]),
                'txtDescription' => 'Advanced features for established businesses'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Enterprise Plan',
                'txtCode' => 'enterprise',
                'decAmount' => 499000.00,
                'intDuration' => 12,
                'jsonFeatures' => json_encode([
                    'max_services' => -1,
                    'max_staff' => -1,
                    'max_bookings_per_month' => -1
                ]),
                'txtDescription' => 'Unlimited features for large businesses'
            ]
        ];
        
        $this->db->table('m_subscription_plans')->insertBatch($plans);

        // Insert default features
        $features = [
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Service Management',
                'txtCode' => 'service_management',
                'txtDescription' => 'Manage services and their details'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Staff Management',
                'txtCode' => 'staff_management',
                'txtDescription' => 'Manage staff and their schedules'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Booking Management',
                'txtCode' => 'booking_management',
                'txtDescription' => 'Manage bookings and appointments'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Payment Processing',
                'txtCode' => 'payment_processing',
                'txtDescription' => 'Process payments through Midtrans'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Analytics & Reports',
                'txtCode' => 'analytics',
                'txtDescription' => 'View business analytics and reports'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'API Access',
                'txtCode' => 'api_access',
                'txtDescription' => 'Access to API endpoints'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Custom Branding',
                'txtCode' => 'custom_branding',
                'txtDescription' => 'Customize branding and theme'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Email Notifications',
                'txtCode' => 'email_notifications',
                'txtDescription' => 'Send email notifications'
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'WhatsApp Notifications',
                'txtCode' => 'whatsapp_notifications',
                'txtDescription' => 'Send WhatsApp notifications'
            ]
        ];
        
        $this->db->table('m_subscription_features')->insertBatch($features);

        // Map features to plans
        $this->mapFeaturesToPlan('free', ['service_management', 'booking_management', 'email_notifications']);
        $this->mapFeaturesToPlan('basic', ['service_management', 'booking_management', 'payment_processing', 
                                         'email_notifications', 'staff_management', 'analytics']);
        $this->mapFeaturesToPlan('premium', ['service_management', 'booking_management', 'payment_processing',
                                           'email_notifications', 'staff_management', 'analytics',
                                           'custom_branding', 'whatsapp_notifications']);
        // Enterprise plan gets all features
        $this->mapFeaturesToPlan('enterprise', array_column($features, 'txtCode'));
    }

    private function mapFeaturesToPlan($planCode, $featureCodes)
    {
        $plan = $this->db->table('m_subscription_plans')
                        ->where('txtCode', $planCode)
                        ->get()
                        ->getRowArray();

        if (!$plan) return;

        $features = $this->db->table('m_subscription_features')
                            ->whereIn('txtCode', $featureCodes)
                            ->get()
                            ->getResultArray();

        $planFeatures = [];
        foreach ($features as $feature) {
            $planFeatures[] = [
                'intPlanID' => $plan['intPlanID'],
                'intFeatureID' => $feature['intFeatureID'],
                'jsonLimits' => json_encode(['enabled' => true, 'quota' => null])
            ];
        }

        if (!empty($planFeatures)) {
            $this->db->table('m_plan_features')->insertBatch($planFeatures);
        }
    }
}
