const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;

$(document).ready(function () {
  loadDataCategory();

  //Event Tambah
  $(document).on("click", "#add-category-btn", function () {
    $("#popup-category-form").css({
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "center",
    });

    // Ketika tombol submit di form popup diklik
    $("#popup-category-form")
      .off("submit")
      .on("submit", function (e) {
        e.preventDefault();
        const nama_category = $("#input-nama-category").val();
        console.log("data", nama_category);
        addCategory(nama_category);
      });
  });

  // Event edit
  $(document).on("click", ".btn-edit", function () {
    const categoryId = $(this).data("id");
    console.log("Edit pengguna dengan ID:", categoryId);
    $("#popup-category-form").css({
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "center",
    });

    // Ketika tombol submit di form popup diklik
    $("#popup-category-form")
      .off("submit")
      .on("submit", function (e) {
        e.preventDefault();
        const nama_category = $("#input-nama-category").val();
        console.log("data", categoryId, nama_category);
        editCategory(categoryId, nama_category);
      });
    $(document).on("click", "#cancel-btn", function () {
      $("#popup-category-form").hide();
    });
  });

  // Event delete
  $(document).on("click", ".btn-delete", function () {
    const categoryId = $(this).data("id");
    console.log("Hapus Category dengan ID:", categoryId);
    const confirmDelete = confirm(
      "Apakah Anda yakin ingin menghapus Category ini?"
    );
    if (!confirmDelete) {
      return;
    }
    deleteDatacategory(categoryId);
  });
});

// Ambil data category
function loadDataCategory() {
  $.ajax({
    url: `${baseUrl}backend/getAllCategory`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      const categoryList = response.categories || [];
      let categoryHTML = "";

      categoryList.forEach((category) => {
        categoryHTML += `
          <tr>
            <td>${category.category_id}</td>
            <td>${category.name_category}</td>
            <td>
              <button class="btn-edit" data-id="${category.category_id}">Edit</button>
              <button class="btn-delete" data-id="${category.category_id}">Hapus</button>
            </td>
          </tr>
        `;
      });

      $("#category-list").html(categoryHTML);
      console.log("Data Category:", categoryList);
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengambil data category:", error);
    },
  });
}

function addCategory(category_name) {
  console.log("data", category_name);
  $.ajax({
    url: `${baseUrl}backend/addCategory`,
    type: "POST",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({ name_category: category_name }),
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

function editCategory(id, nama_category) {
  $.ajax({
    url: `${baseUrl}backend/updateCategoryById`,
    type: "PUT",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({
      id: id,
      name_category: nama_category,
    }),
    success: function (response) {
      console.log("Category berhasil diubah:", response);
      alert("Data Category berhasil diperbarui.");
      window.location.reload(); // Reload halaman untuk melihat perubahan
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengedit Category:", error);
      alert("Gagal mengedit Category.");
    },
  });
}
function deleteDatacategory(categoryId) {
  $.ajax({
    url: `${baseUrl}backend/deleteCategoryById`,
    type: "DELETE",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({ id: categoryId }),
    success: function (response) {
      console.log("Category berhasil dihapus:", response);
      alert("Data Category berhasil dihapus.");
      loadDataCategory(); // Reload data tanpa reload halaman
      $("#popup-category-form").hide(); // Sembunyikan popup jika terbuka
    },
    error: function (xhr, status, error) {
      console.error("Error saat menghapus Category:", error);
      alert("Gagal menghapus Category.");
    },
  });
}
