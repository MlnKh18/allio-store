<?php
$pageTitle = "Admin User Dashboard";
$pageStyle = "style-admin-user-dashboard.css";
$pageScript = "style-admin-user-dashboard.js";
require_once(__DIR__ . '/../includes/header-admin.php');
?>

<div class="c-admin-user-dashboard">
    <div class="c-user-list">
        <h1>Daftar User</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="user-list">
            </tbody>
        </table>
    </div>


    <div class="c-user-form" id="popup-user-form">
        <h2 id="form-title">Tambah User</h2>
        <form id="userForm">
            <input type="hidden" id="userId">
            <label for="name">Nama:</label>
            <input type="text" id="input-nama-user">
            <label for="email">Email:</label>
            <input type="email" id="input-email">
            <label for="role">Role:</label>
            <select id="input-role-id">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" id="save-btn">Simpan</button>
            <button type="button" id="cancel-btn">Batal</button>
        </form>
    </div>


    <!-- Memuat JavaScript sesuai halaman -->
    <?php if (isset($pageScript)) : ?>
        <script src="./assets/js/<?php echo $pageScript; ?>"></script>
    <?php endif; ?>

    <?php require_once(__DIR__ . '../../includes/footer.php'); ?>