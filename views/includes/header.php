<?php
require_once(__DIR__ . '/../includes/session.php');
checkAuth();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Website'; ?></title>

    <!-- CSS Global -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- CSS Dinamis Berdasarkan Halaman -->
    <?php if (isset($pageStyle)) : ?>
        <link rel="stylesheet" href="./assets/css/<?php echo $pageStyle; ?>">
    <?php endif; ?>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="c-content">
        <header>
            <h3>Halo, <?php echo $_SESSION['name_user']; ?></h3>
            <ul>
                <li><a href="./cart"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li><a href="./historyOrder"><i class="fa-solid fa-clock-rotate-left"></i></a></li>
                <li><a href="./#"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        </header>
        <main>