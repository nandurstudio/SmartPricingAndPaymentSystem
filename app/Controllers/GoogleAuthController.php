<?php

namespace App\Controllers;

use Google_Client;
use Google\Service\Oauth2;
use CodeIgniter\Controller;

class GoogleAuthController extends Controller
{
    private $client;
    private $redirectUri = 'http://smartpricingandpaymentsystem.localhost.com/auth/google/callback';

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(getenv('google.client_id'));
        $this->client->setClientSecret(getenv('google.client_secret'));
        $this->client->setRedirectUri($this->redirectUri);
        $this->client->addScope('email');
    }

    // Method untuk mengarahkan user ke Google Login
    public function login()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect()->to($authUrl);
    }

    // Callback setelah user login
    public function callback()
    {
        $code = $this->request->getGet('code');

        if ($code) {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($token['access_token']);

            $oauth = new Oauth2($this->client);
            $user = $oauth->userinfo->get();

            // Simpan data user ke session atau database
            session()->set('user', $user);

            return redirect()->to('/landing'); // Redirect ke halaman dashboard atau sesuai kebutuhan
        } else {
            return redirect()->to('/login'); // Jika gagal, redirect ke halaman login
        }
    }
}