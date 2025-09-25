<?php

/**
 * Google OAuth2 Configuration
 * 
 * Để sử dụng, bạn cần:
 * 1. Tạo project tại Google Cloud Console
 * 2. Enable Google+ API
 * 3. Tạo OAuth 2.0 credentials
 * 4. Cập nhật thông tin client_id và client_secret
 */

use Fuel\Core\Uri;

return array(
    // Google OAuth2 Settings
    'client_id'     => '687034240198-82smvkuf5d48pc5otd7rbfr622f6ru4d.apps.googleusercontent.com',
    'client_secret' => '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',
    'redirect_uri'  => Uri::base() . 'auth/google/callback',
    
    // OAuth2 Scopes
    'scopes' => array(
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ),
    
    // Additional settings
    'access_type'   => 'offline',
    'approval_prompt' => 'force',
    
    // Application name
    'application_name' => 'FuelPHP Blog Application',
);
