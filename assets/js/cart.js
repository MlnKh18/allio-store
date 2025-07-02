const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;

$(document).ready(function () {
  getAllDataCart();
  $("#product-list").on("click", ".btn-remove", function () {
    let productId = $(this).closest(".c-product-item-cart").attr("id");
    console.log("Menghapus produk dengan ID:", productId);
    deleteItemFromCart(productId);
  });
});

function getAllDataCart() {
  let user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    return;
  }
  $.ajax({
    url: `${baseUrl}index.php?page=backend/getCartByUserId/${user.id}`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        let responseData = response.data;
        if (!responseData || responseData.length === 0) {
          $("#product-list").html("<p>Keranjang Anda kosong.</p>");
          return;
        }
        const cartMap = {};
        response.data.forEach((item) => {
          const key = item.name_product; // Group by name_product
          if (cartMap[key]) {
            cartMap[key].quantity += 1;
          } else {
            cartMap[key] = { ...item, quantity: 1 };
          }
        });

        let cartHTML = "";
        Object.values(cartMap).forEach((item) => {
          cartHTML += `
                        <div class="c-product-item-cart" id="${
                          item.product_id
                        }">
                            <h4>${item.name_product}</h4>
                            <p>Qty: ${item.quantity}</p>
                            <p>Harga: ${formatRupiah(item.price)}</p>
                            <button type="button" class="btn-remove">Hapus</button>
                        </div>
                    `;
        });
        $("#product-list").html(cartHTML);
      } else {
        alert("Gagal mendapatkan data keranjang.");
      }
    },
    error: function (error) {
      console.error("Error saat mendapatkan data keranjang:", error);
      alert("Gagal mendapatkan data keranjang.");
    },
  });
}

function deleteItemFromCart(product_id) {
  let user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    return;
  }

  $.ajax({
    url: `${baseUrl}backend/deleteProductFromCart`,
    type: "DELETE",
    contentType: "application/json",
    data: JSON.stringify({ user_id: user.id, product_id: product_id }),
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Item berhasil dihapus dari keranjang.");
        window.location.reload();
      } else {
        alert("Gagal menghapus item dari keranjang.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error saat menghapus item dari keranjang:", error);
      alert("Gagal menghapus item dari keranjang.");
    },
  });
}

function formatRupiah(angka) {
  return parseInt(angka).toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
  });
}
