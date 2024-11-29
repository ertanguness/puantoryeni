$(document).on("click", "#saveTransaction", function () {
  var form = $("#transactionModalForm");
  // Özel doğrulama yöntemi ekleme
  $.validator.addMethod(
    "inputPattern",
    function (value, element) {
      return this.optional(element) || /^[0-9]+[.,]?[0-9]*$/.test(value);
    },
    "Tutar sayısal değer olmalıdır"
  );
  form.validate({
    rules: {
      amount: {
        required: true,
        inputPattern: true
      }
    },
    messages: {
      amount: {
        required: "Lütfen tutar giriniz",
        inputPattern: "Tutar sayısal değer olmalıdır"
      }
    }
  });
  if (!form.valid()) {
    return;
  }

  let formData = new FormData(form[0]);

  formData.append("action", "saveTransaction");

  // for (var pair of formData.entries()) {
  //   console.log(pair[0] + ", " + pair[1]);
  // }

  fetch("/api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status == "success") {
        title = "Başarılı!";
      } else {
        title = "Hata!";
      }

      var transaction = data.transaction;
      var table = $("#transactionTable").DataTable();
      table.row
        .add([
          table.rows().count() + 1,
          transaction.date,
          transaction.type_id,
          transaction.sub_type,
          transaction.case_id,
          transaction.amount,
          transaction.description,
          `<div class="dropdown">
            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item edit-transactions" data-id=${transaction.id}" href="#">
                    <i class="ti ti-edit icon me-3"></i> Güncelle
                </a>
                <a class="dropdown-item delete-transaction" data-id="${transaction.id}" href="#">
                    <i class="ti ti-trash icon me-3"></i> Sil
                </a>
            </div>
        </div>`
        ])
        .draw(false);
      //ilk ve 3. sutüna text-center classı ekle
      [0, 2, 3].forEach((i) =>
        table.column(i).nodes().to$().addClass("text-center")
      );

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

$(document).on("click", ".delete-transaction", function () {
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deleteTransaction";
  let confirmMessage = "Kasa hareketi silinecektir!";
  let url = "/api/financial/transaction.php";

  deleteRecord(this, action, confirmMessage, url);
});

$('input[name="amount"]').keypress(function (e) {
  if ((e.which < 48 || e.which > 57) && e.which != 46) {
    return false;
  }
});

$(document).on("click", ".transaction_type", function () {
  var type = $(this).val();
  var formData = new FormData();
  formData.append("action", "getSubTypes");
  formData.append("type", type);

  fetch("api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      var options = "<option value=''>Tür Seçiniz</option>";
      data = data.subTypes;

      data.forEach((element) => {
        options += `<option value="${element.id}">${element.name}</option>`;
      });
      $("#inc_exp_type").html(options);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});

$(document).on("change", "#firm_cases", function () {
  //case_id'yi al sayfayı post ile yenile
  var case_id = $(this).val();
  var form = $("#caseForm");
  //case_id'yi form'a ekle
  form.append(`<input type="hidden" name="case_id" value="${case_id}">`);
  form.submit();
});

// select2 elemanlarında seçim yapıldığında validator'ı tekrar çalıştır
$(".select2").on("change", function () {
  $(this).valid();
});
//projeden ödeme al
$(document).on("click", "#savePaymentFromProject", function () {
  var id = $("#transaction_id").val();

  addCustomValidationMethods(); //app.js içerisinde tanımlı(validNumber metodu)
  addCustomValidationValidValue(); //app.js içerisinde tanımlı(validValue metodu)
  var form = $("#paymentFromProjectForm");
  form.validate({
    rules: {
      fp_project_name: {
        required: true,
        validValue: true
      },
      fp_amount: {
        required: true,
        validNumber: true
      },
      fp_cases: {
        required: true,
        validValue: true
      }
    },
    messages: {
      fp_project_name: {
        required: "Lütfen proje seçin",
        validValue: "Lütfen proje seçin"
      },
      fp_amount: {
        required: "Lütfen miktarı girin",
        validNumber: "Geçerli bir miktar girin"
      },
      fp_cases: {
        required: "Lütfen kasa seçin",
        validValue: "Lütfen kasa seçin"
      }
    },
    errorPlacement: function (error, element) {
      customErrorPlacement(error, element);
    }
  });
  if (!form.valid()) {
    return;
  }
  let formData = new FormData(form[0]);
  formData.append("action", "getPaymentFromProject");
  formData.append("id", id);

  fetch("api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);

      if (data.status == "success") {
        Swal.fire({
          title: "Başarılı!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      } else {
        Swal.fire({
          title: "Hata!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        });
      }
    });
});

//Personele ödeme yap
$(document).on("click", "#savePayToPerson", function () {
  var form = $("#payToPersonForm");

  addCustomValidationMethods(); //app.js içerisinde tanımlı(validNumber metodu)
  addCustomValidationValidValue(); //app.js içerisinde tanımlı(validValue metodu)
  form.validate({
    rules: {
      tp_person_name: {
        required: true,
        validValue: true
      },
      tp_amount: {
        required: true,
        validNumber: true
      },
      tp_cases: {
        required: true,
        validValue: true
      }
    },
    messages: {
      tp_person_name: {
        validValue: "Lütfen bir personel seçin"
      },
      tp_amount: {
        required: "Lütfen miktarı girin",
        validNumber: "Geçerli bir miktar girin"
      },
      tp_cases: {
        required: "Lütfen bir kasa seçin",
        validValue: "Lütfen bir kasa seçin"
      }
    },
    errorPlacement: function (error, element) {
      customErrorPlacement(error, element);
    }
  });
  if (!form.valid()) {
    return;
  }


  let id = $("#transaction_id").val();
  let formData = new FormData(form[0]);

  formData.append("action", "payToPerson");
  formData.append("id", id);

  fetch("api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);

      if (data.status == "success") {
        Swal.fire({
          title: "Başarılı!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      } else {
        Swal.fire({
          title: "Hata!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        });
      }
    });
});

//Firma Ödemesi yap
$(document).on("click", "#savePayToCompany", function () {
  let id = $("#transaction_id").val();
  var form = $("#payToCompanyForm");

  addCustomValidationMethods(); //app.js içerisinde tanımlı(validNumber metodu)
  addCustomValidationValidValue(); //app.js içerisinde tanımlı(validValue metodu)
  form.validate({
    rules: {
      tc_company_name: {
        required: true,
        validValue: true
      },
      tc_amount: {
        required: true,
        validNumber: true
      },
      tc_cases: {
        required: true,
        validValue: true
      }
    },
    messages: {
      tc_company_name: {
        required: "Lütfen bir firma seçin",
        validValue: "Lütfen bir firma seçin"
      },
      tc_amount: {
        required: "Lütfen miktarı girin",
        validNumber: "Geçerli bir miktar girin"
      },
      tc_cases: {
        required: "Lütfen bir kasa seçin",
        validValue: "Lütfen bir kasa seçin"
      }
    },
    errorPlacement: function (error, element) {
      customErrorPlacement(error, element);
    }
  });
  if (!form.valid()) {
    return;
  }



  let formData = new FormData(form[0]);

  formData.append("action", "payToCompany");
  formData.append("id", id);

  // for (var pair of formData.entries()) {
  //   console.log(pair[0] + ", " + pair[1]);
  // }

  fetch("api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);

      if (data.status == "success") {
        Swal.fire({
          title: "Başarılı!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      } else {
        Swal.fire({
          title: "Hata!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        });
      }
    });
});

//Alınan Proje Masraf Ekle
$(document).on("click", "#saveAddExpenseReceivedProject", function () {
  let id = $("#transaction_id").val();
  var form = $("#addExpenseReceivedProjectForm");

  addCustomValidationMethods(); //app.js içerisinde tanımlı(validNumber metodu)
  addCustomValidationValidValue(); //app.js içerisinde tanımlı(validValue metodu)
  form.validate({
    rules: {
      rp_project_name: {
        required: true,
        validValue: true
      },
      rp_amount: {
        required: true,
        validNumber: true
      },
      rp_cases: {
        required: true,
        validValue: true
      }
    },
    messages: {
      rp_project_name: {
        required: "Lütfen bir proje seçin",
        validValue: "Lütfen bir proje seçin"
      },
      rp_amount: {
        required: "Lütfen miktarı girin",
        validNumber: "Geçerli bir miktar girin"
      },
      rp_cases: {
        required: "Lütfen bir kasa seçin",
        validValue: "Lütfen bir kasa seçin"
      }
    },
    errorPlacement: function (error, element) {
      customErrorPlacement(error, element);
    }
  });
  if (!form.valid()) {
    return;
  }




  let formData = new FormData(form[0]);

  formData.append("action", "addExpenseReceivedProject");
  formData.append("id", id);

  // for (var pair of formData.entries()) {
  //   console.log(pair[0] + ", " + pair[1]);
  // }

  fetch("api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);

      if (data.status == "success") {
        Swal.fire({
          title: "Başarılı!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      } else {
        Swal.fire({
          title: "Hata!",
          text: data.message,
          icon: data.status,
          confirmButtonText: "Tamam"
        });
      }
    });
});

//Güncelleme işlemi
$(document).on("click", ".edit-transactions", function () {
  let id = $(this).data("id");
  $("#transaction_id").val(id);

  var projects = "";
  var cases = "";
  var persons = "";
  var companies = "";

  //preloader göster
  $(".preloader").show();

  //tablonun 2. satırındaki veriyi al
  let type = $(this).closest("tr").find("td:eq(3)").text().trim();

  switch (type) {
    case "Proje(Alınan Ödeme)":
      var modal = $("#get_payment_from_project-modal");
      var case_select = $("#fp_cases");
      var project_select = $("#fp_project_name");
      var amount_input = "fp_amount";
      var date_input = "fp_action_date";
      var description_input = "fp_description";

      break;

    case "Personel Ödemesi":
      var modal = $("#pay_to_person-modal");
      var case_select = $("#tp_cases");
      var person_select = $("#tp_person_name");
      var amount_input = "tp_amount";
      var date_input = "tp_action_date";
      var description_input = "tp_description";
      break;

    case "Firma Ödemesi":
      var modal = $("#pay_to_company-modal");
      var case_select = $("#tc_cases");
      var companies_select = $("#tc_company_name");
      var amount_input = "tc_amount";
      var date_input = "tc_action_date";
      var description_input = "tc_description";

      break;
    case "Alınan Proje Masrafı":
      var modal = $("#add_expense_received_project-modal");
      var case_select = $("#rp_cases");
      var project_select = $("#rp_project_name");
      var amount_input = "rp_amount";
      var date_input = "rp_action_date";
      var description_input = "rp_description";
      break;

    case "Virman":
      swal.fire({
        title: "Uyarı!",
        text: "Virman işlemi buradan güncellenemez!",
        icon: "error",
        confirmButtonText: "Tamam"
      });
      //preload gizle
      $(".preloader").hide();
      return;
    default:
      var modal = $("#general-modal");
      break;
  }

  //kasanın tüm değerleri bir değişkene atanır,
  case_select.find("option").each(function () {
    if ($(this).val() != 0) {
      cases += $(this).val() + ",";
    }
  });

  //Projenin tüm değerleri bir değişkene atanır,
  if (project_select) {
    project_select.find("option").each(function () {
      if ($(this).val() != 0) {
        projects += $(this).val() + ",";
      }
    });
  }
  //Personel selectin tüm değerleri bir değişkene atanır,
  if (person_select) {
    person_select.find("option").each(function () {
      if ($(this).val() != 0) {
        persons += $(this).val() + ",";
      }
    });
  }

  //Firma selectin tüm değerleri bir değişkene atanır,
  if (companies_select) {
    companies_select.find("option").each(function () {
      if ($(this).val() != 0) {
        companies += $(this).val() + ",";
      }
    });
  }

  var formData = new FormData();
  formData.append("id", id);
  formData.append("action", "getTransaction");
  formData.append("cases", cases);
  formData.append("projects", projects);
  formData.append("persons", persons);
  formData.append("companies", companies);

  fetch("api/financial/transaction.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status == "success") {
        var project_id = data.transaction.project_id;
        var person_id = data.transaction.person_id;
        var company_id = data.transaction.company_id;
        var case_id = data.transaction.case_id;
        var amount = data.transaction.amount;
        var date = data.transaction.date;
        var description = data.transaction.description;

        // console.log(data.transaction);

        if (project_select) {
          project_select.val(project_id).trigger("change");
        }
        if (person_select) {
          person_select.val(person_id).trigger("change");
        }
        if (companies_select) {
          companies_select.val(company_id).trigger("change");
        }

        case_select.val(case_id).trigger("change");
        amount_input = $("input[name='" + amount_input + "']").val(amount);
        date_input = $("input[name='" + date_input + "']").val(date);
        description_input = $("textarea[name='" + description_input + "']").val(
          description
        );
        modal.modal("show");

        //preloader gizle
        $(".preloader").hide();
      }
    });
});

// Fetch isteğinden dönen veriyi kullanarak işlemler yapan fonksiyon
function processTransactionData() {}

function customErrorPlacement(error, element) {
  if (element.hasClass("select2")) {
    error.insertAfter(element.next("span"));
  } else {
    error.insertAfter(element);
  }
}
