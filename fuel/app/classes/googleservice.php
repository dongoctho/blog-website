<?php

use Fuel\Core\Config;
use Fuel\Core\Log;

/**
 * Google Service Helper
 * Xử lý Google OAuth2 authentication
 */
class GoogleService 
{
    private $client;
    private $service;
    
    public function __construct()
    {
        // Load Google API Client library
        require_once realpath(__DIR__ . '/../../vendor/autoload.php');

        
        // Khởi tạo Google Client
        $this->client = new Google_Client();
        
        // Cấu hình từ config file
        $config = Config::load('google');
        
        $this->client->setClientId($config['client_id']);
        $this->client->setClientSecret($config['client_secret']);
        $this->client->setRedirectUri($config['redirect_uri']);
        $this->client->setScopes($config['scopes']);
        $this->client->setAccessType($config['access_type']);
        $this->client->setApprovalPrompt($config['approval_prompt']);
        $this->client->setApplicationName($config['application_name']);

        // Khởi tạo Google+ service
        $this->service = new Google_Service_Oauth2($this->client);
    }
    
    /**
     * Lấy URL để redirect đến Google OAuth
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }
    
    /**
     * Xử lý authorization code từ Google callback
     */
    public function authenticate($code)
    {
        try {
            // Exchange authorization code với access token
            $this->client->authenticate($code);
            
            // Lấy access token
            $access_token = $this->client->getAccessToken();
            
            if ($access_token) {
                $this->client->setAccessToken($access_token);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            Log::error('Google authentication error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin user từ Google
     */
    public function getUserInfo()
    {
        try {
            if (!$this->client->getAccessToken()) {
                return false;
            }
            
            // Lấy thông tin user từ Google+ API
            $userinfo = $this->service->userinfo->get();
            
            return array(
                'google_id' => $userinfo->id,
                'email' => $userinfo->email,
                'name' => $userinfo->name,
                'given_name' => $userinfo->givenName,
                'family_name' => $userinfo->familyName,
                'picture' => $userinfo->picture,
                'verified_email' => $userinfo->verifiedEmail,
                'locale' => $userinfo->locale
            );
            
        } catch (Exception $e) {
            Log::error('Error getting Google user info: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Revoke access token
     */
    public function revokeToken()
    {
        try {
            if ($this->client->getAccessToken()) {
                $this->client->revokeToken();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::error('Error revoking Google token: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra access token có hợp lệ không
     */
    public function isAccessTokenValid()
    {
        try {
            $access_token = $this->client->getAccessToken();
            
            if ($access_token && !$this->client->isAccessTokenExpired()) {
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            Log::error('Error checking access token: ' . $e->getMessage());
            return false;
        }
    }
}
