<?php
$pageTitle = "Admin Category Dashboard";
$pageStyle = "style-admin-category-dashboard.css";
$pageScript = "style-admin-category-dashboard.js";
require_once(__DIR__ . '/../includes/header-admin.php');
?>

<div class="c-admin-category-dashboard">
    <div class="c-category-list">
        <div class="c-action-category" style="display: flex; justify-content: space-between;">
            <h1>Daftar category</h1>
            <button type="button" id="add-category-btn">Tambah category <i class="fa-solid fa-plus"></i></button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Category</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="category-list">
            </tbody>
        </table>
    </div>


    <div class="c-category-form" id="popup-category-form">
        <h2 id="form-title">Tambah category</h2>
        <form id="categoryForm">
            <input type="hidden" id="categoryId">
            <label for="name">Nama Category:</label>
            <input type="text" id="input-nama-category">
            <button type="submit" id="save-btn">Simpan</button>
            <button type="button" id="cancel-btn">Batal</button>
        </form>
    </div>


    <!-- Memuat JavaScript sesuai halaman -->
    <?php if (isset($pageScript)) : ?>
        <script src="./assets/js/<?php echo $pageScript; ?>"></script>
    <?php endif; ?>

    <?php require_once(__DIR__ . '../../includes/footer.php'); ?>