$(document).on("click", ".add-progress-payment", function () {
  let project_id = $(this).data("id");
  if (!checkId(project_id, "Projeyi")) {
    return;
  }
  $("#progress-payment-modal").modal("show");

  let project_name = $(this).closest("tr").find("td:eq(2)").text();

  $("#progress_payment_project_name").text(project_name);
  $("#progress_payment_project_id").val(project_id);
});

function addCustomValidationMethods() {
  $.validator.addMethod("validNumber", function (value, element) {
    return this.optional(element) || /^[0-9.,]+$/.test(value);
  }, "Lütfen geçerli bir sayı girin");
}

$(document).on("click", "#progress_payment_addButton", function () {


  addCustomValidationMethods();


  var form = $("#progress_payment_modalForm");
  var formData = new FormData(form[0]);

  form.validate({
    rules: {
      progress_payment_amount: {
        required: true,
        validNumber: true,
      },
      progress_payment_date: {
        required: true
      }
    },
    messages: {
      progress_payment_amount: {
        required: "Lütfen miktarı girin",
        validNumber: "Geçerli bir miktar girin",
      },
      progress_payment_date: {
        required: "Tarih seçin"
      }
    }
  });
  if (!form.valid()) {
    return;
  }

  formData.append("action", "add_progress_payment");

  fetch("api/projects/progress-payment.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);

      let progress_payment = data.progress_payment;
      var table = $("#project_paymentTable").DataTable();
      table.row
        .add([
          progress_payment.id,
          progress_payment.tarih,
          progress_payment.turu,
          progress_payment.ay,
          progress_payment.yil,
          `<i class='ti ti-download icon color-green me-1' ></i>
          ${progress_payment.kategori}`,
          progress_payment.tutar,
          progress_payment.aciklama,
          progress_payment.created_at,
          `<div class="dropdown">
                      <button class="btn dropdown-toggle align-text-top"
                          data-bs-toggle="dropdown">İşlem</button>
                      <div class="dropdown-menu dropdown-menu-end">
                          <a class="dropdown-item edit-payment"
                              data-id='${progress_payment.id}'>
                              <i class="ti ti-edit icon me-3"></i> Güncelle
                          </a>
                          <a class="dropdown-item delete-payment" href="#" data-id='${progress_payment.id}'>
                              <i class="ti ti-trash icon me-3"></i> Sil
                          </a>
                      </div>
                  </div>`
        ])
        .order([0, "desc"])
        .draw(false);

      if (data.status == "success") {
        title = "Başarılı";
        let summary = data.summary;
        $("#progress_payment_modalForm").trigger("reset");
        $("#total_income").text(summary.hakedis);
        $("#total_expense").text(summary.kesinti);
        $("#total_payment").text(summary.odeme);
        $("#balance").text(summary.balance);
      } else {
        title = "Hata";
      }
      swal.fire({
        title: title,
        text: data.message,
        icon: data.status
      }).then((result) => {
        let page = getUrlParameter("p");
        // console.log(page);
        if (page == "projects/list") {
          location.reload();
        }

      });;
    });
});
function getUrlParameter(name) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  var results = regex.exec(location.search);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}