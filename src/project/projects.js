$(document).on("click", "#saveProject", function () {
  var form = $("#projectForm");

  let formData = new FormData(form[0]);
  for (var pair of formData.entries()) {
    console.log(pair[0] + ", " + pair[1]);
  }

  fetch("/api/projects/projects.php", {
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
        title: title,
        text: data.message,
        icon: data.status,
        confirmButtonText: "Tamam",
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
        title: title,
        text: data.message,
        icon: data.status,
        confirmButtonText: "Tamam",
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
  getTowns($(this).val(),"#project_town");
});

