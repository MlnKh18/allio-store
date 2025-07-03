<?php
$pageTitle = "Admin Product Dashboard";
$pageStyle = "style-admin-product-dashboard.css";
$pageScript = "style-admin-product-dashboard.js";
require_once(__DIR__ . '/../includes/header-admin.php');
?>

<div class="c-admin-product-dashboard">
    <div class="c-product-list">
        <div class="c-action-product" style="display: flex; justify-content: space-between;">
            <h1>Daftar Produk</h1>
            <button type="button" id="add-product-btn">Tambah Produk <i class="fa-solid fa-plus"></i></button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="product-list"></tbody>
        </table>
    </div>

    <div class="c-product-form" id="popup-product-form">
        <h2 id="form-title">Tambah Produk</h2>
        <form id="productForm">
            <input type="hidden" id="productId">
            <label for="name">Nama Produk:</label>
            <input type="text" id="input-nama-product" required>
            <label for="price">Harga:</label>
            <input type="number" id="input-price-product" required>
            <label for="description">Deskripsi:</label>
            <textarea id="input-description-product"></textarea>
            <label for="image">URL Gambar:</label>
            <input type="text" id="input-image-product" required>
            <label for="category">Kategori:</label>
            <select id="input-category-id">
                <option value="">-- Pilih Kategori --</option>

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