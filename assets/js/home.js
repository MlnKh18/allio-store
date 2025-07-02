const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;

$(document).ready(function () {
  loadDataCategory();
  loadAllDataProduct();
});

function loadDataCategory() {
  $.ajax({
    url: `${baseUrl}backend/getAllCategory`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      let categoryList = response.categories;
      console.log("Data kategori:", categoryList);

      let categoryHTML = "";
      for (let i = 0; i < categoryList.length; i++) {
        categoryHTML += `
          <div class="c-category-item" id="${categoryList[i].category_id}">
            <h4>${categoryList[i].name_category}</h4>
          </div>
        `;
      }

      // Tampilkan ke elemen dengan id category-list
      $("#c-category-list").html(categoryHTML);
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengambil data kategori:", error);
    },
  });
}

function loadAllDataProduct() {
  $.ajax({
    url: `${baseUrl}backend/getAllProduct`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      let productList = response.data;
      console.log("Data produk:", productList);

      let productHTML = "";
      for (let i = 0; i < productList.length; i++) {
        productHTML += `
          <div class="c-product-item" id="${productList[i].product_id}">
                    <img src="${productList[i].image_url}" alt="Produk 1">
                    <h4>${productList[i].name_product}</h4>
                    <p>${productList[i].description}</p>
                    <span>Harga: ${formatRupiah(productList[i].price)}</span>
                    <button>Tambah ke Keranjang</button>
          </div>
        `;
        console.log("Produk ID:", productList[i].image_url);
      }

      // Tampilkan ke elemen dengan id product-list
      $("#product-list").html(productHTML);
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengambil data produk:", error);
    },
  });
}

function filterData(data, filter) {
  let now = new Date();
  return data.filter((log) => {
    let logDate = new Date(log.created_at);
    switch (filter) {
      case "week":
        let weekAgo = new Date();
        weekAgo.setDate(now.getDate() - 7);
        return logDate >= weekAgo;
      case "month":
        let monthAgo = new Date();
        monthAgo.setMonth(now.getMonth() - 1);
        return logDate >= monthAgo;
      case "year":
        let yearAgo = new Date();
        yearAgo.setFullYear(now.getFullYear() - 1);
        return logDate >= yearAgo;
      default:
        return true;
    }
  });
}

function formatRupiah(angka) {
  return parseInt(angka).toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
  });
}

function formatDate(dateString) {
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
  };
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", options);
}
