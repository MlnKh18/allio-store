<?php
require_once(__DIR__ . '/../services/userService.php');
require_once(__DIR__ . '/../models/userModel.php');

class UserController
{
    public $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function handleGetAllUser()
    {
        return $this->userService->handleGetAllUser();
    }
}
