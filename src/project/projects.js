$(document).on("click", "#saveProject", function () {
  var form = $("#projectForm");
  let formData = new FormData(form[0]);
  // for (var pair of formData.entries()) {
  //   console.log(pair[0] + ", " + pair[1]);
  // }

  form.validate({
    rules: {
      project_name: {
        required: true
      }
    },
    messages: {
      project_name: {
        required: "Proje adı zorunludur."
      }
    }
  });

  if (!form.valid()) {
    return false;
  }

  fetch("/api/projects/projects.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      if (data.status == "success") {
        $("#id").val(data.lastInsertId);
        title = "Başarılı!";
      } else {
        title = "Hata!";
      }
      Swal.fire({
        title: title,
        text: data.message,
        icon: data.status,
        confirmButtonText: "Tamam"
      });
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});

$(document).on("click", "#savePersontoProject", function () {
  var checkedItems = [];
  $("#addPersontoProject tbody tr").each(function () {
    var checkbox = $(this).find("input[type='checkbox']");
    if (checkbox.prop("checked")) {
      checkedItems.push(checkbox.val());
    }
  });

  let formData = new FormData();
  formData.append("project_id", $("#project_id").val());
  formData.append("person_id", checkedItems);
  formData.append("action", "addPersonToProject");

  fetch("/api/projects/project-person.php", {
    method: "POST",
    body: formData
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
        title: title,
        text: data.message,
        icon: data.status,
        confirmButtonText: "Tamam"
      });
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});

$(document).ready(function () {
  // "Tümünü Seç" checkbox'ının durumunu kontrol edin
  $("#allPersonCheck").change(function () {
    // "Tümünü Seç" checkbox'ının durumu true ise tüm personel checkbox'larını işaretleyin, değilse işaretlerini kaldırın
    var isChecked = $(this).is(":checked");
    $("#addPersontoProject .form-check-input").prop("checked", isChecked);
  });
});

$(document).on("change", "#project_city", function () {
  //İl id'si alınır ilce selectine ilceler yüklenir
  getTowns($(this).val(), "#project_town");
});

$(document).on("click", ".delete-project", function () {
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deleteProject";
  let confirmMessage = "Proje silinecektir!";
  let url = "/api/projects/projects.php";

  deleteRecord(this, action, confirmMessage, url);
});

$(document).ready(function () {
  // DataTable'ı başlat
  var table = $("#projectTable").DataTable();

  // Radyo butonuna tıklama olayını dinle
  $(".form-selectgroup-input").on("change", function () {
    var type = $(this).attr("data-type");
    //Eğer tümü ise tüm filtreleri kaldır
    if (type == "Tümü") {
      table.column(1).search("").draw();
      return;
    }
    if (this.checked) {
      // DataTable'da filtreleme yap
      table.column(1).search(type).draw();
    }
  });
function filterTableByCheckedRadio() {
  var checkedRadio = $(".form-selectgroup-input:checked");
  if (checkedRadio.length > 0) {
    var type = checkedRadio.attr("data-type");
    if (type == "Tümü") {
      table.column(1).search("").draw();
    } else {
      table.column(1).search(type).draw();
    }
  }
}

// Sayfa yüklendiğinde tabloyu filtrele
filterTableByCheckedRadio();
});
