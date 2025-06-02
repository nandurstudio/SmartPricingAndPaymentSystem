<?php

if (!function_exists('get_profile_picture')) {
    /**
     * Get the profile picture URL for a user
     * 
     * @param string|null $photoPath The stored photo path/URL
     * @return string The URL to the profile picture
     */
    function get_profile_picture($photoPath = null) {
        // If photo is a full URL (e.g. from Google auth)
        if (filter_var($photoPath, FILTER_VALIDATE_URL)) {
            return $photoPath;
        }

        // If photo exists in uploads folder
        if ($photoPath && $photoPath !== 'default.png' && file_exists(ROOTPATH . 'public/uploads/photos/' . $photoPath)) {
            return base_url('uploads/photos/' . $photoPath);
        }

        // Return a random default avatar
        return get_random_profile_picture();
    }
}

if (!function_exists('get_random_profile_picture')) {
    /**
     * Get a random profile picture from the available defaults
     * 
     * @return string The URL to a random default profile picture
     */
    function get_random_profile_picture() {
        $pictures = [
            'profile-1.png',
            'profile-2.png',
            'profile-3.png',
            'profile-4.png',
            'profile-5.png',
            'profile-6.png'
        ];
        
        $randomIndex = array_rand($pictures);
        return base_url('assets/img/illustrations/profiles/' . $pictures[$randomIndex]);
    }
}
