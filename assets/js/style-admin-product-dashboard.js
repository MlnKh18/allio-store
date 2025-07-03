const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;
let categoriesList = [];

$(document).ready(function () {
  getCategories().then(() => {
    loadDataProduct();
  });

  // Event Tambah Product
  $(document).on("click", "#add-product-btn", function () {
    $("#popup-product-form").css({
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "center",
    });
    populateCategoryOptions(); // ← ini dia

    $("#popup-product-form")
      .off("submit")
      .on("submit", function (e) {
        e.preventDefault();
        const name_product = $("#input-nama-product").val();
        const harga = $("#input-price-product").val();
        const deskripsi = $("#input-description-product").val();
        const image = $("#input-image-product").val();
        const category_id = $("#input-category-id").val();
        console.log("data", name_product, harga, deskripsi, image, category_id);
        addProduct(name_product, harga, deskripsi, image, category_id);
      });
  });

  // Event edit
  $(document).on("click", ".btn-edit", function () {
    const productId = $(this).data("id");
    const name_product = $(this).data("name");
    const price = parseFloat($(this).data("price"));
    const description = $(this).data("description");
    const image_url = $(this).data("image");
    const category_id = $(this).data("category");

    console.log("Edit product ID:", productId);

    $("#popup-product-form").css({
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "center",
    });

    // Populate kategori option dulu
    populateCategoryOptions();

    // Isi form-nya
    $("#input-nama-product").val(name_product);
    $("#input-price-product").val(price);
    $("#input-description-product").val(description);
    $("#input-image-product").val(image_url);
    $("#input-category-id").val(category_id);

    // Submit form edit product
    $("#popup-product-form")
      .off("submit")
      .on("submit", function (e) {
        e.preventDefault();

        const updated_name_product = $("#input-nama-product").val();
        const updated_price = parseFloat($("#input-price-product").val());
        const updated_description = $("#input-description-product").val();
        const updated_image_url = $("#input-image-product").val();
        const updated_category_id = parseInt($("#input-category-id").val());

        // Validasi sebelum kirim
        if (
          !productId ||
          !updated_name_product.trim() ||
          isNaN(updated_price) ||
          updated_price <= 0 ||
          !updated_description.trim() ||
          !updated_image_url.trim() ||
          isNaN(updated_category_id) ||
          updated_category_id <= 0
        ) {
          alert("Mohon lengkapi semua data product dengan benar.");
          return;
        }

        // Debug data
        const dataToSend = {
          product_id: productId,
          name_product: updated_name_product,
          price: updated_price,
          description: updated_description,
          image_url: updated_image_url,
          categoryId: updated_category_id,
        };
        console.log(
          "📝 Data yang dikirim:",
          JSON.stringify(dataToSend, null, 2)
        );

        editProduct(
          productId,
          updated_name_product,
          updated_price,
          updated_description,
          updated_image_url,
          updated_category_id
        );
      });
    // Event Cancel popup product
    $(document).on("click", "#cancel-btn", function () {
      $("#popup-product-form").hide();
    });
  });
  $(document).on("click", ".btn-delete", function () {
    const productId = $(this).data("id");
    console.log("Hapus Product dengan ID:", productId);
    const confirmDelete = confirm(
      "Apakah Anda yakin ingin menghapus Product ini?"
    );
    if (!confirmDelete) {
      return;
    }
    deleteDataProduct(productId);
  });
});

// ✅ Ambil data kategori (dipisah)
function getCategories() {
  return $.ajax({
    url: `${baseUrl}backend/getAllCategory`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      categoriesList = response.categories;
      console.log("✔️ Data kategori:", categoriesList);
    },
    error: function (xhr, status, error) {
      console.error("❌ Error saat mengambil data kategori:", error);
    },
  });
}

// ✅ Load data product (menggunakan categoriesList yang sudah di-load sebelumnya)
function loadDataProduct() {
  const categoryMap = {};
  categoriesList.forEach((category) => {
    categoryMap[category.category_id] = category.name_category;
  });

  $.ajax({
    url: `${baseUrl}backend/getAllProduct`,
    type: "GET",
    dataType: "json",
    success: function (productResponse) {
      const productList = productResponse.data || [];
      let productHTML = "";

      productList.forEach((product) => {
        const categoryName =
          categoryMap[product.category_id] || "Kategori Tidak Diketahui";
        productHTML += `
<tr>
  <td>${product.product_id}</td>
  <td>${product.name_product}</td>
  <td>Rp ${parseInt(product.price).toLocaleString("id-ID")}</td>
  <td>${product.description}</td>
  <td>
    <img src="${product.image_url || ""}" 
         alt="Gambar Produk" 
         style="max-width: 80px; max-height: 80px;" />
  </td>
  <td>${categoryName}</td>
  <td>
    <button class="btn-edit" 
            data-id="${product.product_id}"
            data-name="${product.name_product}"
            data-price="${product.price}"
            data-description="${product.description}"
            data-image="${product.image_url}"
            data-category="${product.category_id}"
    >Edit</button>
    <button class="btn-delete" data-id="${product.product_id}">Hapus</button>
  </td>
</tr>
`;
      });

      $("#product-list").html(productHTML);
      console.log("✔️ Data product:", productList);
    },
    error: function (xhr, status, error) {
      console.error("❌ Error saat mengambil data product:", error);
    },
  });
}

function addProduct(name_product, price, description, image_url, category_id) {
  console.log("data", name_product, price, description, image_url, category_id);
  $.ajax({
    url: `${baseUrl}backend/addProduct`,
    type: "POST",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({
      name_product: name_product,
      price: price,
      description: description,
      image_url: image_url,
      categoryId: category_id,
    }),
    success: function (response) {
      console.log("Category berhasil ditambahkan:", response);
      alert("Data Category berhasil ditambahkan.");
      window.location.reload(); // Reload halaman untuk melihat perubahan
    },
    error: function (xhr, status, error) {
      console.error("Error saat menambahkan Category:", error);
      alert("Gagal menambahkan Category.");
    },
  });
}

function editProduct(
  product_id,
  name_product,
  price,
  description,
  image_url,
  category_id
) {
  $.ajax({
    url: `${baseUrl}backend/updateProductById`,
    type: "PUT",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({
      product_id: product_id,
      name_product: name_product,
      price: price,
      description: description,
      image_url: image_url,
      categoryId: category_id,
    }),
    success: function (response) {
      console.log("✔️ Product berhasil diubah:", response);
      alert("Data Product berhasil diperbarui.");
      window.location.reload();
    },
    error: function (xhr, status, error) {
      console.error("❌ Error saat mengedit Product:", error);
      alert("Gagal mengedit Product.");
    },
  });
}

function deleteDataProduct(ProductId) {
  $.ajax({
    url: `${baseUrl}backend/deleteProductById`,
    type: "DELETE",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({ product_id: ProductId }),
    success: function (response) {
      console.log("Category berhasil dihapus:", response);
      alert("Data Category berhasil dihapus.");
      loadDataProduct(); // Reload data tanpa reload halaman
      $("#popup-category-form").hide(); // Sembunyikan popup jika terbuka
    },
    error: function (xhr, status, error) {
      console.error("Error saat menghapus Category:", error);
      alert("Gagal menghapus Category.");
    },
  });
}

function populateCategoryOptions() {
  const categorySelect = $("#input-category-id");
  categorySelect.empty(); // Kosongkan dulu option-nya

  categorySelect.append(`<option value="">-- Pilih Kategori --</option>`);

  categoriesList.forEach((category) => {
    categorySelect.append(
      `<option value="${category.category_id}">${category.name_category}</option>`
    );
  });
}
