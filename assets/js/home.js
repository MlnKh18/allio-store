const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;
let selectedCategoryId = null;
let productResponse = null;

$(document).ready(function () {
  loadDataCategory();
  loadAllDataProduct();
  $("#c-category-list").on("click", ".c-category-item", function () {
    let categoryId = $(this).attr("id");
    selectedCategoryId = categoryId;
    console.log("Kategori yang dipilih:", categoryId);

    if (productResponse) {
      loadProductByCategory(selectedCategoryId, productResponse);
    } else {
      console.warn("Data produk belum tersedia.");
    }
  });
  $(document).on("click", ".add-to-cart-btn", function () {
    let productId = $(this).data("id");
    addItemToCart(productId);
  });
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
      productResponse = response; // simpan ke variabel global
      let productList = response.data;
      console.log("Data produk:", productList);

      let productHTML = "";
      for (let i = 0; i < productList.length; i++) {
        productHTML += `
        <div class="c-product-item" id="${productList[i].product_id}">
    <img src="${productList[i].image_url}" alt="Produk">
    <h4>${productList[i].name_product}</h4>
    <p>${productList[i].description}</p>
    <hr/>
    <span>Harga: ${formatRupiah(productList[i].price)}</span>
    <button class="add-to-cart-btn" data-id="${
      productList[i].product_id
    }">Tambah ke Keranjang</button>
  </div>
        `;
      }

      $("#product-list").html(productHTML);

      $("#search-button")
        .off("click")
        .on("click", function () {
          searchProductByName(response);
        });
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengambil data produk:", error);
    },
  });
}

function searchProductByName(response) {
  // Pastikan input dan response valid
  if (!response || !response.data || !Array.isArray(response.data)) {
    $("#product-list").html("<p>Data produk tidak tersedia.</p>");
    return;
  }

  let searchQuery = $("#search-input").val();
  if (typeof searchQuery !== "string") searchQuery = "";
  searchQuery = searchQuery.trim().toLowerCase();

  let productList = response.data;
  let filteredData = productList;

  $("#product-filter").html(searchQuery ? searchQuery : "Semua");

  if (searchQuery) {
    filteredData = productList.filter((product) => {
      // Pastikan properti ada dan bertipe string
      if (
        product &&
        typeof product.name_product === "string" &&
        product.name_product.toLowerCase().includes(searchQuery)
      ) {
        return true;
      }
      return false;
    });
  }

  let productHTML = "";
  if (filteredData.length > 0) {
    for (let i = 0; i < filteredData.length; i++) {
      let p = filteredData[i];
      productHTML += `
        <div class="c-product-item" id="${p.product_id || ""}">
          <img src="${p.image_url || "#"}" alt="Produk">
          <h4>${p.name_product || "Tanpa Nama"}</h4>
          <p>${p.description || ""}</p>
          <hr/>
          <span>Harga: ${formatRupiah(p.price || 0)}</span>
          <button>Tambah ke Keranjang</button>
        </div>
      `;
    }
  } else {
    productHTML = "<p>Produk tidak ditemukan.</p>";
  }

  $("#product-list").html(productHTML);
}

function loadProductByCategory(_categoryId, response) {
  console.log("Memuat produk untuk kategori:", _categoryId);
  let productList = response.data || [];
  // Filter produk berdasarkan category_id
  let filteredProducts = productList.filter(
    (product) => String(product.category_id) === String(_categoryId)
  );
  let productHTML = "";
  if (filteredProducts.length > 0) {
    for (let i = 0; i < filteredProducts.length; i++) {
      productHTML += `
      <div class="c-product-item" id="${filteredProducts[i].product_id}">
    <img src="${filteredProducts[i].image_url}" alt="Produk">
    <h4>${filteredProducts[i].name_product}</h4>
    <p>${filteredProducts[i].description}</p>
    <hr/>
    <span>Harga: ${formatRupiah(filteredProducts[i].price)}</span>
    <button class="add-to-cart-btn" data-id="${
      filteredProducts[i].product_id
    }">Tambah ke Keranjang</button>
  </div>
      `;
    }
  } else {
    productHTML = "<p>Produk tidak ditemukan untuk kategori ini.</p>";
  }
  $("#product-list").html(productHTML);
}

function addItemToCart(productId) {
  let userData = localStorage.getItem("user");
  if (!userData) {
    alert("Silakan login terlebih dahulu.");
    return;
  }

  let user;
  try {
    user = JSON.parse(userData);
  } catch (e) {
    console.error("User data corrupt:", e);
    alert("Data user rusak, silakan login ulang.");
    return;
  }

  if (!user.id) {
    alert("Silakan login terlebih dahulu.");
    return;
  }

  $.ajax({
    url: `${baseUrl}backend/addProductToCart`,
    type: "POST",
    data: JSON.stringify({
      user_id: user.id,
      product_id: productId,
      quantity: 1,
    }),
    dataType: "json",
    success: function (response) {
      console.log("Response:", response);
      if (response.status === "success") {
        alert("Produk berhasil ditambahkan ke keranjang!");
      } else {
        alert("Gagal: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error saat menambahkan produk ke keranjang:", error);
      alert("Gagal menambahkan produk ke keranjang.");
    },
  });
}

function formatRupiah(angka) {
  return parseInt(angka).toLocaleString("id-ID", {
    style: "currency",
    currency: "IDR",
  });
}
