<?php
$pageTitle = "History_Order";
$pageStyle = "history.css";
$pageScript = "history.js";
require_once(__DIR__ . '/../includes/header.php');
?>

<div class="c-order">
    <h2>History Order</h2>
    <section id="ctn-product">
        <div class="c-product-section">

            <div id="order-list">
                <p>Belum ada order</p>
            </div>

            <hr>

        </div>
    </section>
</div>


<!-- Memuat JavaScript sesuai halaman -->
<?php if (isset($pageScript)) : ?>
    <script src="./assets/js/<?php echo $pageScript; ?>"></script>
<?php endif; ?>

<?php require_once(__DIR__ . '../../includes/footer.php'); ?>