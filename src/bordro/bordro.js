
// $(document).on("click", "#wage_cut_addButton", function () {
//   let form = $("#wage_cut_modalForm");
//   addWageCutorIncome(form);
// });
// $(document).on("click", ".add-wage-cut", function () {
//   let personel_id = $(this).data("id");
//   let personel_name = $(this).closest("tr").find("td:eq(1)").text();
//   $("#person_id_wage_cut").val(personel_id);
//   $("#person_name_wage_cut").text(personel_name);
// });


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


// Bordro hesapla butonuna tıklandığında
$(document).on("click", "#payroll_calculate", function () {
  //POST işlemi için form oluşturuluyor
  let form = $("#bordroInfoForm");
  form.append('<input type="hidden" name="action" value="payroll_calculate">');
  form.submit();
});
