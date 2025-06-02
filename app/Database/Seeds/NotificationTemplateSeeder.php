<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run()
    {
        // First, clear existing templates
        $this->db->table('m_notification_templates')->emptyTable();
        
        // Define default notification templates
        $templates = [
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intTenantID' => null,
                'txtType' => 'booking_confirmation',
                'txtName' => 'Booking Confirmation Email',
                'txtChannel' => 'email',
                'txtSubject' => 'Booking Confirmation #{txtBookingCode}',
                'txtContent' => '<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; }
        .content { padding: 20px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; }
        .details { background-color: #f8f9fa; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Booking Confirmation</h2>
        </div>
        <div class="content">
            <p>Dear {customer_name},</p>
            <p>Your booking has been confirmed with the following details:</p>
            
            <div class="details">
                <p>Booking Code: <strong>{booking_code}</strong></p>
                <p>Service: {service_name}</p>
                <p>Date: {booking_date}</p>
                <p>Time: {start_time} - {end_time}</p>
                <p>Price: {price}</p>
            </div>
            
            <p>View your booking details here:</p>
            <p><a href="{booking_url}" class="button">View Booking</a></p>
        </div>
        <div class="footer">
            <p>© {current_year} {tenant_name}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>',
                'jsonVariables' => json_encode([
                    'tenant_name', 'customer_name', 'booking_code', 'service_name',
                    'booking_date', 'start_time', 'end_time', 'price', 'status',
                    'booking_url', 'customer_email', 'current_year'
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intTenantID' => null,
                'txtType' => 'payment_confirmation',
                'txtName' => 'Payment Confirmation Email',
                'txtChannel' => 'email',
                'txtSubject' => 'Payment Confirmation for Booking #{booking_code}',
                'txtContent' => '<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; }
        .content { padding: 20px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; }
        .details { background-color: #f8f9fa; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Confirmation</h2>
        </div>
        <div class="content">
            <p>Dear {customer_name},</p>
            <p>We have received your payment for booking <strong>{booking_code}</strong>.</p>
            
            <div class="details">
                <p>Amount Paid: <strong>{amount}</strong></p>
                <p>Payment Method: {payment_method}</p>
                <p>Transaction ID: {transaction_id}</p>
                <p>Payment Date: {payment_date}</p>
            </div>
            
            <p>View your payment details here:</p>
            <p><a href="{booking_url}" class="button">View Details</a></p>
        </div>
        <div class="footer">
            <p>© {current_year} {tenant_name}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>',
                'jsonVariables' => json_encode([
                    'tenant_name', 'customer_name', 'booking_code', 'amount',
                    'payment_method', 'transaction_id', 'payment_date',
                    'booking_url', 'current_year'
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'intTenantID' => null,
                'txtType' => 'booking_reminder',
                'txtName' => 'Booking Reminder Email',
                'txtChannel' => 'email',
                'txtSubject' => 'Reminder: Your Booking Tomorrow',
                'txtContent' => '<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; }
        .content { padding: 20px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; }
        .details { background-color: #f8f9fa; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Booking Reminder</h2>
        </div>
        <div class="content">
            <p>Dear {customer_name},</p>
            <p>This is a reminder for your booking tomorrow.</p>
            
            <div class="details">
                <p>Booking Code: <strong>{booking_code}</strong></p>
                <p>Service: {service_name}</p>
                <p>Date: {booking_date}</p>
                <p>Time: {start_time} - {end_time}</p>
            </div>
            
            <p>View your booking details here:</p>
            <p><a href="{booking_url}" class="button">View Booking</a></p>
        </div>
        <div class="footer">
            <p>© {current_year} {tenant_name}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>',
                'jsonVariables' => json_encode([
                    'tenant_name', 'customer_name', 'booking_code', 'service_name',
                    'booking_date', 'start_time', 'end_time',
                    'booking_url', 'current_year'
                ]),
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Insert all templates
        foreach ($templates as $template) {
            $this->db->table('m_notification_templates')->insert($template);
        }
    }
}
