const port = "8080";
const baseUrl = `http://localhost:${port}/toko-online-allinone/`;

$(document).ready(function () {
  loadDataUser();

  // Event edit
  $(document).on("click", ".btn-edit", function () {
    const userId = $(this).data("id");
    console.log("Edit pengguna dengan ID:", userId);
    $("#popup-user-form").css({
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "center",
    });

    // Ketika tombol submit di form popup diklik
    $("#popup-user-form")
      .off("submit")
      .on("submit", function (e) {
        e.preventDefault();
        const nama_user = $("#input-nama-user").val();
        const email = $("#input-email").val();
        const role_id = $("#input-role-id").val();
        console.log("data", userId, nama_user, email, role_id);
        editUser(userId, nama_user, email, role_id);
      });
    $(document).on("click", "#cancel-btn", function () {
      
      $("#popup-user-form").hide();
    });
  });

  // Event delete
  $(document).on("click", ".btn-delete", function () {
    const userId = $(this).data("id");
    console.log("Hapus pengguna dengan ID:", userId);
    const confirmDelete = confirm(
      "Apakah Anda yakin ingin menghapus pengguna ini?"
    );
    if (!confirmDelete) {
      return;
    }
    deleteDataUser(userId);
  });
});

// Ambil data user
function loadDataUser() {
  $.ajax({
    url: `${baseUrl}backend/getAllUser`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      const userList = response.users || [];
      let userHTML = "";

      userList.forEach((user) => {
        userHTML += `
          <tr>
            <td>${user.id}</td>
            <td>${user.name_user}</td>
            <td>${user.email}</td>
            <td>${user.role == 1 ? "Admin" : "User"}</td>
            <td>
              <button class="btn-edit" data-id="${user.id}">Edit</button>
              <button class="btn-delete" data-id="${user.id}">Hapus</button>
            </td>
          </tr>
        `;
      });

      $("#user-list").html(userHTML);
      console.log("Data pengguna:", userList);
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengambil data pengguna:", error);
    },
  });
}

function editUser(userId, nama_user, email, role_id) {
  $.ajax({
    url: `${baseUrl}backend/editUserById`,
    type: "PATCH",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({
      user_id: userId,
      name_user: nama_user,
      email: email,
      role_id: parseInt(role_id),
    }),
    success: function (response) {
      console.log("Pengguna berhasil diubah:", response);
      alert("Data pengguna berhasil diperbarui.");
      window.location.reload(); // Reload halaman untuk melihat perubahan
    },
    error: function (xhr, status, error) {
      console.error("Error saat mengedit pengguna:", error);
      alert("Gagal mengedit pengguna.");
    },
  });
}
function deleteDataUser(userId) {
  $.ajax({
    url: `${baseUrl}backend/deleteUserById`,
    type: "DELETE",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify({ user_id: userId }),
    success: function (response) {
      console.log("Pengguna berhasil dihapus:", response);
      alert("Data pengguna berhasil dihapus.");
      window.location.reload(); // Reload halaman untuk melihat perubahan
    },
    error: function (xhr, status, error) {
      console.error("Error saat menghapus pengguna:", error);
      alert("Gagal menghapus pengguna.");
    },
  });
}
