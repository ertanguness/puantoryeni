
console.log("burası çalıştı " + $(".datatable").length);
$(document).on("click", "#rol_kaydet", function () {
  var form = $("#roleForm");

  let formData = new FormData(form[0]);
  formData.append("id", $("#role_id").val());
  formData.append("action", "saveRoles");

  for (data of formData.entries()) {
    console.log(data);
  }

  fetch("api/users/roles.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data.status == "success") {
        title = "Başarılı!";
      } else {
        title = "Hata!";
      }
      Swal.fire({
        icon: data.status,
        title: title,
        text: data.message,
      });
    });
});

$(document).on("click", ".delete_role", function () {
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deleteRole";
  let confirmMessage = "Rol silinecektir!";
  let url = "/api/users/roles.php";

  deleteRecord(this, action, confirmMessage, url);
});
