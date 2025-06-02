<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run()
    {
        // First, empty the table
        $this->db->table('m_service_types')->emptyTable();
        
        // Define initial service types
        $serviceTypes = [
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Futsal Court',
                'txtSlug' => 'futsal-court',
                'txtDescription' => 'Futsal court booking service for indoor soccer activities',
                'txtIcon' => 'fas fa-futbol',
                'txtCategory' => 'Sports',
                'bitIsSystem' => 1,
                'bitIsApproved' => 1,
                'jsonDefaultAttributes' => json_encode([
                    'indoor_outdoor' => 'Indoor',
                    'surface_type' => 'Synthetic Grass',
                    'dimensions' => '25m x 15m',
                    'facilities' => ['Changing Room', 'Shower', 'Parking']
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Villa Rental',
                'txtSlug' => 'villa-rental',
                'txtDescription' => 'Villa and vacation home rental service',
                'txtIcon' => 'fas fa-home',
                'txtCategory' => 'Accommodation',
                'bitIsSystem' => 1,
                'bitIsApproved' => 1,
                'jsonDefaultAttributes' => json_encode([
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'max_guests' => 6,
                    'amenities' => ['WiFi', 'Pool', 'Kitchen', 'Air Conditioning', 'Parking']
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Beauty Salon',
                'txtSlug' => 'beauty-salon',
                'txtDescription' => 'Beauty salon services including haircuts, manicures, and more',
                'txtIcon' => 'fas fa-cut',
                'txtCategory' => 'Beauty & Wellness',
                'bitIsSystem' => 1,
                'bitIsApproved' => 1,
                'jsonDefaultAttributes' => json_encode([
                    'service_duration' => 60,
                    'gender' => 'Unisex',
                    'services' => ['Haircut', 'Coloring', 'Styling', 'Manicure', 'Pedicure']
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtName' => 'Course & Workshop',
                'txtSlug' => 'course-workshop',
                'txtDescription' => 'Educational courses and skill-building workshops',
                'txtIcon' => 'fas fa-chalkboard-teacher',
                'txtCategory' => 'Education',
                'bitIsSystem' => 1,
                'bitIsApproved' => 1,
                'jsonDefaultAttributes' => json_encode([
                    'class_size' => 20,
                    'duration_weeks' => 4,
                    'schedule_type' => 'Weekly',
                    'delivery_mode' => ['Online', 'In-Person']
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert service types
        foreach ($serviceTypes as $type) {
            $this->db->table('m_service_types')->insert($type);
        }

        // Insert service type attributes
        $this->createServiceTypeAttributes();
    }

    private function createServiceTypeAttributes()
    {
        // First, empty the table
        $this->db->table('m_service_type_attributes')->emptyTable();

        // Get service type IDs
        $futsalType = $this->db->table('m_service_types')->where('txtSlug', 'futsal-court')->get()->getRow();
        $villaType = $this->db->table('m_service_types')->where('txtSlug', 'villa-rental')->get()->getRow();
        $salonType = $this->db->table('m_service_types')->where('txtSlug', 'beauty-salon')->get()->getRow();
        $courseType = $this->db->table('m_service_types')->where('txtSlug', 'course-workshop')->get()->getRow();

        $attributes = [
            // Futsal Court attributes
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $futsalType->intServiceTypeID,
                'txtName' => 'indoor_outdoor',
                'txtLabel' => 'Indoor/Outdoor',
                'txtFieldType' => 'select',
                'jsonOptions' => json_encode(['Indoor', 'Outdoor']),
                'bitRequired' => 1,
                'txtDefaultValue' => 'Indoor',
                'intDisplayOrder' => 1,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $futsalType->intServiceTypeID,
                'txtName' => 'surface_type',
                'txtLabel' => 'Surface Type',
                'txtFieldType' => 'select',
                'jsonOptions' => json_encode(['Synthetic Grass', 'Rubber', 'Wood']),
                'bitRequired' => 1,
                'txtDefaultValue' => 'Synthetic Grass',
                'intDisplayOrder' => 2,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],

            // Villa Rental attributes
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $villaType->intServiceTypeID,
                'txtName' => 'bedrooms',
                'txtLabel' => 'Number of Bedrooms',
                'txtFieldType' => 'number',
                'bitRequired' => 1,
                'txtDefaultValue' => '3',
                'txtValidation' => 'min:1|max:10',
                'intDisplayOrder' => 1,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $villaType->intServiceTypeID,
                'txtName' => 'max_guests',
                'txtLabel' => 'Maximum Guests',
                'txtFieldType' => 'number',
                'bitRequired' => 1,
                'txtDefaultValue' => '6',
                'txtValidation' => 'min:1|max:20',
                'intDisplayOrder' => 2,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],

            // Beauty Salon attributes
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $salonType->intServiceTypeID,
                'txtName' => 'service_duration',
                'txtLabel' => 'Service Duration (minutes)',
                'txtFieldType' => 'number',
                'bitRequired' => 1,
                'txtDefaultValue' => '60',
                'txtValidation' => 'min:15|max:240',
                'intDisplayOrder' => 1,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $salonType->intServiceTypeID,
                'txtName' => 'gender',
                'txtLabel' => 'Service For',
                'txtFieldType' => 'select',
                'jsonOptions' => json_encode(['Male', 'Female', 'Unisex']),
                'bitRequired' => 1,
                'txtDefaultValue' => 'Unisex',
                'intDisplayOrder' => 2,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],

            // Course & Workshop attributes
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $courseType->intServiceTypeID,
                'txtName' => 'class_size',
                'txtLabel' => 'Maximum Class Size',
                'txtFieldType' => 'number',
                'bitRequired' => 1,
                'txtDefaultValue' => '20',
                'txtValidation' => 'min:1|max:100',
                'intDisplayOrder' => 1,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intServiceTypeID' => $courseType->intServiceTypeID,
                'txtName' => 'delivery_mode',
                'txtLabel' => 'Delivery Mode',
                'txtFieldType' => 'select',
                'jsonOptions' => json_encode(['Online', 'In-Person', 'Hybrid']),
                'bitRequired' => 1,
                'txtDefaultValue' => 'In-Person',
                'intDisplayOrder' => 2,
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert all attributes
        foreach ($attributes as $attribute) {
            $this->db->table('m_service_type_attributes')->insert($attribute);
        }
    }
}
