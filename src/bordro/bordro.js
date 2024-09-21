$(document).on("click", "#wage_cut_addButton", function () {
  let form = $("#wage_cut_modalForm");
  addWageCutorIncome(form);
});
$(document).on("click", "#income_addButton", function () {
  let form = $("#income_modalForm");
  addWageCutorIncome(form,"saveIncome","income");
});




$(document).on("click", ".add-wage-cut", function () {
  let personel_id = $(this).data("id");
  let personel_name = $(this).closest("tr").find("td:eq(1)").text();
  $("#person_id").val(personel_id);
  $("#person_name").text(personel_name);
});

$(document).on("click", ".add-income", function () {
  let personel_id = $(this).data("id");
  let personel_name = $(this).closest("tr").find("td:eq(1)").text();
  $("#person_id_income").val(personel_id);
  $("#person_name_income").text(personel_name);
});

function addWageCutorIncome(form  ,action ="saveWageCut",apiLink="wage_cut" ){ 
  let formData = new FormData(form[0]);
  formData.append("action", action);

  for(let pair of formData.entries()) {
    console.log(pair[0]+ ', '+ pair[1]); 
  }

  fetch("api/bordro/" + apiLink + ".php", {
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
      }).then((result) => {
        if (result.isConfirmed) {
          location.reload();
        }
      });
      form[0].reset();
    });
}


$("#projects").on("change", function () {
  Route();
});

//Yıl değiştiği zaman sayfayı yeniden yükle
$("#year").on("change", function () {
  Route();
});

//Ay değiştiği zaman sayfayı yeniden yükle
$("#months").on("change", function () {
  Route();
});

function Route() {
  var form = $("#bordroInfoForm");
  form.submit();
}
