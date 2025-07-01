<?php
session_start();

// Cek apakah user sudah login atau belum
//Jika Belum login tidak bisa mengakses halaman dan kembali ke halaman login

function isUserLoggedIn()
{
    return isset($_SESSION['id_pegawai']);
}
function checkAuth()
{
    $allowedPages = ['./login', './register'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    if (!isUserLoggedIn() && !in_array($currentPage, $allowedPages)) {
        header("Location: ./login");
        exit;
    }
}
