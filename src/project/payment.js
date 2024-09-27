$(document).on("click", ".add-payment", function () {
  let project_id = $(this).data("id");
  let project_name = $(this).closest("tr").find("td:eq(2)").text();
  $("#payment_project_name").text(project_name);
  $("#payment_project_id").val(project_id);
});

$(document).on("click", "#payment_addButton", function () {
  var form = $("#payment_modalForm");
  var formData = new FormData(form[0]);

  form.validate({
    rules: {
      payment_amount: {
        required: true,
        number: true,
      },
      payment_date: {
        required: true,
      },
    },
    messages: {
      payment_amount: {
        required: "Lütfen miktarı girin",
        number: "Geçerli bir miktar girin",
      },
      payment_date: {
        required: "Tarih seçin",
      },
    },
  });
  if (!form.valid()) {
    return;
  }

  formData.append("action", "add_payment");

  for (var pair of formData.entries()) {
    console.log(pair[0] + ", " + pair[1]);
  }

  fetch("api/projects/payment.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status == "success") {
        console.log(data);
        let payment = data.last_payment;
        var table = $("#person_paymentTable").DataTable();
        table.row
          .add([
            payment.id,
            payment.tarih,
            payment.turu,
            payment.ay,
            payment.yil,
            `<i class='ti ti-upload icon color-yellow me-1' ></i>
            ${payment.kategori}`,
            payment.tutar,
            payment.aciklama,
            payment.created_at,
            `<div class="dropdown">
              <button class="btn dropdown-toggle align-text-top"
                  data-bs-toggle="dropdown">İşlem</button>
              <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item edit-payment"
                      data-id='${payment.id}'>
                      <i class="ti ti-edit icon me-3"></i> Güncelle
                  </a>
                  <a class="dropdown-item delete-payment" href="#" data-id='${payment.id}'>
                      <i class="ti ti-trash icon me-3"></i> Sil
                  </a>
              </div>
          </div>`,
          ])
          .order([0, "desc"])
          .draw(false);

          let summary = data.summary;
            $("#total_income").text(summary.hakedis);
            $("#total_expense").text(summary.kesinti);
            $("#total_payment").text(summary.odeme);
            $("#balance").text(summary.balance);

        title = "Başarılı";
        $("#payment-modalForm").trigger("reset");
      } else {
        title = "Hata";
      }
      swal.fire({
        title: title,
        text: data.message,
        icon: data.status,
      });
    });
});
