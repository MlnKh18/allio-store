const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;
let cartMap = {};
let total_amount = 0;

$(document).ready(function () {
  getAllDataCart();
  $("#product-list").on("click", ".btn-remove", function () {
    let productId = $(this).closest(".c-product-item-cart").attr("id");
    console.log("Menghapus produk dengan ID:", productId);
    deleteItemFromCart(productId);
  });
  $("#checkout-button").on("click", function () {
    console.log("Melakukan checkout dengan total harga:", total_amount);
    checkout();
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
          total_amount += item.price * item.quantity;
        });
        $("#product-list").html(cartHTML);
        $("#checkout-button").text(formatRupiah(total_amount));
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
function checkout() {
  let user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    return;
  }
  console.log("Melakukan checkout untuk user:", user.id);
  $.ajax({
    url: `${baseUrl}backend/checkout`,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ user_id: user.id, total_amount: total_amount }),
    dataType: "json",
    success: function (response) {
      console.log("Response dari server:", response);
      if (response.status === "success") {
        alert("Checkout berhasil!");
        deleteAllItemsFromCart(user.id); // Hapus semua item setelah checkout
        window.location.reload();
      } else {
        alert("Gagal melakukan checkout.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error saat melakukan checkout:", error);
      alert("Gagal melakukan checkout.");
    },
  });
}

function deleteAllItemsFromCart(userId) {


  $.ajax({
    url: `${baseUrl}backend/clearCart`,
    type: "DELETE",
    contentType: "application/json",
    data: JSON.stringify({ user_id: userId }),
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
