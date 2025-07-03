const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;

$(document).ready(function () {
  getAllDataOrderWithPayment();
  $("#order-list").on("click", ".btn-remove", function () {
    let orderId = $(this).closest(".c-order-item").attr("id");
    if (!orderId) {
      alert("ID order tidak ditemukan.");
      return;
    }
    console.log("Menghapus order dengan ID:", orderId);
    deleteOrder(orderId);
  });
});

function getAllDataOrderWithPayment() {
  let user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    return;
  }

  $.ajax({
    url: `${baseUrl}index.php?page=backend/getOrderHistoryWithPaymentByUserId/${user.id}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Data order berhasil diambil:", response);

      if (response.status !== "success") {
        alert("Gagal mengambil data order.");
        return;
      }

      let orderList = response.data;
      let orderHTML = "";

      orderList.forEach((order) => {
        orderHTML += `
          <div class="c-order-item" id="${order.order_id}">
            <h3>Order ID: ${order.order_id}</h3>
            <p>Status: ${order.status}</p>
            <p>Metode Pembayaran: ${order.payment_method}</p>
            <p>Tanggal: ${order.created_at}</p>
            <p>Total: ${formatRupiah(order.total_amount)}</p>
            <button type="button" class="btn-remove">Hapus</button>


            <div class="c-product-list">
        `;

        order.order_items.forEach((item) => {
          orderHTML += `
            <div class="c-product-item-cart">
              <h4>${item.product_name}</h4>
              <p>Qty: ${item.quantity}</p>
              <p>Harga: ${formatRupiah(item.price)}</p>
            </div>
          `;
        });

        orderHTML += `</div></div>`;
      });

      $("#order-list").html(orderHTML);
    },

    error: function (error) {
      console.error("Terjadi kesalahan saat mengambil data order:", error);
      alert("Gagal mengambil data order.");
    },
  });
}
function deleteOrder(orderId) {
  let user = JSON.parse(localStorage.getItem("user"));
  if (!user) {
    alert("Silakan login terlebih dahulu.");
    return;
  }
  $.ajax({
    url: `${baseUrl}index.php?page=backend/deleteOrder`,
    method: "DELETE",
    dataType: "json",
    data: JSON.stringify({ order_id: orderId, user_id: user.id }),
    success: function (response) {
      if (response.status === "success") {
        alert("Order berhasil dihapus.");
        getAllDataOrderWithPayment(); // Refresh the order list
      } else {
        alert("Gagal menghapus order.");
      }
    },
    error: function (error) {
      console.error("Terjadi kesalahan saat menghapus order:", error);
      alert("Gagal menghapus order.");
    },
  });
}

function formatRupiah(angka) {
  return parseInt(angka).toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
  });
}
