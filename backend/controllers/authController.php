<?php
require_once(__DIR__ . '/../services/authService.php');
require_once(__DIR__ . '/../models/userModel.php');

class AuthController
{
    public $authService;
    public function __construct()
    {
        $this->authService = new authService();
    }
    public function handleLogin($email, $password)
    {
        return $this->authService->handleLogin($email, $password);
    }
    public function handleRegister($username, $email, $password)
    {
        return $this->authService->handleRegister($username, $email, $password);
    }
    public function handleLogout()
    {
        return $this->authService->handleLogout();
    }
}
