<?php

namespace App\Controllers;

class DebugController extends BaseController
{
    // Method to debug Google user registration
    public function testGoogleRegistration()
    {
        // Check if user has proper permissions
        if (session()->get('roleID') != 1) { // Assuming roleID 1 is admin
            return $this->response->setJSON(['error' => 'Unauthorized access']);
        }
        
        // Create a mock Google user object
        $googleUser = new \stdClass();
        $googleUser->id = 'google_' . uniqid();
        $googleUser->email = 'test_user_' . time() . '@gmail.com';
        $googleUser->name = 'Test Google User';
        $googleUser->givenName = 'Test';
        $googleUser->familyName = 'User';
        $googleUser->picture = 'https://example.com/default.png';
        
        // Email parts for username
        $emailParts = explode('@', $googleUser->email);
        $userName = $emailParts[0]; // Part before @
        
        // Profile picture URL
        $profilePictureUrl = $googleUser->picture;
        $profilePictureUrlHD = str_replace("=s96-c", "", $profilePictureUrl);
        
        // Full name
        $fullName = trim(($googleUser->givenName ?? '') . ' ' . ($googleUser->familyName ?? ''));
        if (empty($fullName)) {
            $fullName = $googleUser->email;
        }
        
        // Create the user data array as it would be in the Google registration
        $newUserData = [
            'txtEmail' => $googleUser->email,
            'txtUserName' => $userName,
            'txtFullName' => $fullName,
            'txtPhoto' => $profilePictureUrlHD,
            'bitActive' => 1,
            'intRoleID' => 5, // Customer role
            'dtmJoinDate' => date('Y-m-d H:i:s'),
            'dtmLastLogin' => date('Y-m-d H:i:s'),
            'txtGUID' => uniqid('google_', true), // Generate with prefix for tracking            'dtmCreatedDate' => date('Y-m-d H:i:s'),
            'txtCreatedBy' => 'google_auth',
            'txtGoogleAuthToken' => $googleUser->id,
        ];
        
        // Return the data as JSON for inspection
        return $this->response->setJSON([
            'success' => true,
            'message' => 'This shows the data that would be created for a Google user registration',
            'userData' => $newUserData
        ]);
    }
    
    // Method to check txtGUID values
    public function checkGuidValues()
    {
        // Check if user has proper permissions
        if (session()->get('roleID') != 1) { // Assuming roleID 1 is admin
            return $this->response->setJSON(['error' => 'Unauthorized access']);
        }
        
        $userModel = new \App\Models\MUserModel();
        $users = $userModel->select('intUserID, txtUserName, txtEmail, txtGUID, google_auth_token')
                          ->limit(10)
                          ->get()
                          ->getResultArray();
                          
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Sample of user records with txtGUID values',
            'users' => $users
        ]);
    }
}
