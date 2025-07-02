<?php
$pageTitle = "Home Dashboard";
$pageStyle = "home.css";
$pageScript = "home.js";
require_once(__DIR__ . '/../includes/header.php');
?>

<div class="c-home-dashboard">
    <h2>Selamat Datang di ALLIO STORE</h2>
    <section id="ctn-product">
        <div class="c-category-section">
            <h3>Kategori</h3>
            <div id="c-category-list">
                <div class="c-category-item">
                    <h4>Kategori 1</h4>
                </div>
                <div class="c-category-item">
                    <h4>Kategori 2</h4>
                </div>
                <div class="c-category-item">
                    <h4>Kategori 3</h4>
                </div>
            </div>
        </div>
        <div class="c-product-section">
            <div class="c-product-search">
                <h3>Daftar Produk (<span id="product-filter">Semua</span>)</h3>
                <input type="text" id="search-input" placeholder="Cari produk...">
                <button type="button" id="search-button" class="product-search-button">Cari</button>
            </div>
            <div class="c-product-list" id="product-list">
            </div>
        </div>
    </section>
</div>

</main>
</div>

<!-- Memuat JavaScript sesuai halaman -->
<?php if (isset($pageScript)) : ?>
    <script src="./assets/js/<?php echo $pageScript; ?>"></script>
<?php endif; ?>

<?php require_once(__DIR__ . '../../includes/footer.php'); ?>