$(document).on("click", "#kullanici_kaydet", function () {
  let id = $("#user_id").val();
  var form = $("#userForm");

  form.validate({
    rules: {
      full_name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
      },
      user_roles: {
        required: true,
      },
    },
    messages: {
      full_name: {
        required: "Lütfen kullanıcı adını giriniz",
      },
      email: {
        required: "Lütfen email adresini giriniz",
        email: "Lütfen geçerli bir email adresi giriniz",
      },
      password: {
        required: "Lütfen şifreyi giriniz",
      },
      user_roles: {
        required: "Lütfen kullanıcı rolünü seçiniz",
      },
    },
    errorPlacement: function (error, element) {
      if (element.hasClass("select2")) {
        // select2 konteynerini bul
        var container = element.next(".select2-container");
        // Hata mesajını, select2 konteynerinin sonuna ekler
        error.insertAfter(container);
      } else {
        // Diğer tüm durumlar için varsayılan davranış
        error.insertAfter(element);
      }
    },
  });
  if (!form.valid()) {
    return;
  }

  var formData = new FormData(form[0]);
  formData.append("id", id);
  formData.append("action", "userSave");

  //   for (data of formData.entries()) {
  //     console.log(data);
  //   }

  fetch("api/users/users.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status == "success") {
        title = "Başarılı!";
        $("#user_id").val(data.lastid);
      } else {
        title = "Hata!";
      }
      swal.fire({
        title: title,
        text: data.message,
        icon: data.status,
        confirmButtonText: "Tamam",
      });
    });
});

$(document).on("click", ".delete_user", function () {
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deleteUser";
  let confirmMessage = "Kullanıcı silinecektir!";
  let url = "/api/users/users.php";

  deleteRecord(this,  action, confirmMessage, url);
});
