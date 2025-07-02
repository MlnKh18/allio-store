<?php
$pageTitle = "Cart";
$pageStyle = "cart.css";
$pageScript = "cart.js";
require_once(__DIR__ . '/../includes/header.php');
?>

<div class="c-cart">
    <h2>Keranjang Belanja</h2>
    <section id="ctn-product">
        <div class="c-product-section">
            <div class="c-product-list" id="product-list">
                <div class="c-product-item-cart">
                    <h4>Produk 1</h4>
                    <p>1</p>
                    <p>Harga: Rp. 100.000</p>
                    <button type="button" class="btn-remove">Hapus</button>
                </div>
            </div>
            <button type="button" id="checkout-button">Checkout</button>
    </section>
</div>

<!-- Memuat JavaScript sesuai halaman -->
<?php if (isset($pageScript)) : ?>
    <script src="./assets/js/<?php echo $pageScript; ?>"></script>
<?php endif; ?>

<?php require_once(__DIR__ . '../../includes/footer.php'); ?>