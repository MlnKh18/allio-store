$(document).ready(function () {
  // Tampilkan popup profile saat ikon user diklik
  $('header ul li a[href="./#"]').on("click", function (e) {
    e.preventDefault();
    let user = JSON.parse(localStorage.getItem("user"));
    if (!user) {
      alert("Silakan login terlebih dahulu.");
      return;
    }

    // Tampilkan data profil
    $("#profile-name").text(user.name_user);
    $("#profile-email").text(user.email);

    // Munculkan popup
    $("#popup-profile").fadeIn();
  });

  // Tutup popup saat tombol Tutup diklik
  $("#btn-close-profile").on("click", function () {
    $("#popup-profile").fadeOut();
  });

  // Logout user saat tombol Logout diklik
  $("#btn-logout").on("click", function () {
    localStorage.removeItem("user");
    alert("Anda berhasil logout.");
    window.location.href = "./login"; // Redirect ke halaman login
  });
});
