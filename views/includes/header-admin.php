<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="c-content" id="c-content-admin">
        <aside>
            <h1>Halo, <?= isset($_SESSION['name_user']) ? htmlspecialchars($_SESSION['name_user']) : 'Pengguna' ?></h1>

            <ul>
                <li><a href="./user-dashboard">User</a></li>
                <li><a href="./category-dashboard">Category</a></li>
                <li><a href="./product">Product</a></li>
            </ul>
        </aside>
        <main>