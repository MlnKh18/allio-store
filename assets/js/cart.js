const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;
let cartMap = {};
let total_amount = 0;
let paymentMethod = "";

$(document).ready(function () {
  getAllDataCart();
  $("#product-list").on("click", ".btn-remove", function () {
    let productId = $(this).closest(".c-product-item-cart").attr("id");
    console.log("Menghapus produk dengan ID:", productId);
  });
  $("#checkout-button").on("click", function () {
    if (Object.keys(cartMap).length === 0) {
      alert("Keranjang Anda kosong.");
      return false;
    }
    $("#popup-paymentMetod").css({
      display: "flex",
      justifyContent: "center",
      alignItems: "center",
    });
  });

  $("#popup-paymentMetod").on("click", "button:not(.btn-payment)", function () {
    $("#popup-paymentMetod").css({
      display: "none",
    });
  });

  $("#popup-paymentMetod").on("click", ".btn-payment", function () {
    paymentMethod = $(this).attr("id");
    console.log("Metode pembayaran yang dipilih:", paymentMethod);
    if (paymentMethod === "payment-cod") {
      alert("Anda memilih Bayar di Tempat (COD).");
    } else if (paymentMethod === "payment-transfer") {
      alert("Anda memilih Transfer Bank.");
    }
    $("#popup-paymentMetod").hide();
    console.log("Melakukan checkout dengan total harga:", total_amount);
    checkout(paymentMethod);
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
                            <p class="quantity">${item.quantity}</p>
                            <p class="harga" data-harga="${
                              item.price
                            }">Harga: ${formatRupiah(item.price)}</p>
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
function checkout(paymentMethod) {
  let user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    return;
  }

  let totalAmount = 0;
  $(".c-product-item-cart").each(function () {
    let harga = parseInt($(this).find(".harga").data("harga"));
    let quantity =
      parseInt($(this).find(".quantity").text().replace(/[^\d]/g, "")) || 1;
    totalAmount += harga * quantity;
  });
  console.log("Total amount:", totalAmount);

  if (totalAmount <= 0) {
    alert("Tidak ada item di keranjang.");
    return;
  }

  console.log(
    "Melakukan checkout untuk user:",
    user.id,
    "dengan total:",
    totalAmount
  );

  $.ajax({
    url: `${baseUrl}backend/checkout`,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({
      user_id: user.id,
      total_amount: totalAmount,
      payment_method: paymentMethod,
    }),
    dataType: "json",
    success: function (response) {
      console.log("Response dari server:", response);
      if (response.status === "success") {
        alert("Checkout berhasil!");
        deleteAllItemsFromCart(user.id);
        window.location.reload();
      } else {
        alert("Gagal melakukan checkout.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error saat melakukan checkout:", xhr.responseText);
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
