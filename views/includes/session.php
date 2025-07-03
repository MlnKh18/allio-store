<?php
session_start();

// Cek apakah user sudah login atau belum
//Jika Belum login tidak bisa mengakses halaman dan kembali ke halaman login

function isUserLoggedIn()
{
    return isset($_SESSION['user_id']);
}
function checkAuth()
{
    $allowedPages = ['./login', './register'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Jika belum login dan bukan di halaman login/register
    if (!isUserLoggedIn() && !in_array($currentPage, $allowedPages)) {
        header("Location: ./login");
        exit;
    }

    // Jika sudah login dan role admin, arahkan ke dashboard admin
    if (isUserLoggedIn() && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
        if ($currentPage !== 'user-dashboard') {
            header("Location: ./user-dashboard");
            exit;
        }
    }
}
