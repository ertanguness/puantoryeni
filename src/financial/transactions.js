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
        inputPattern: true,
      },
    },
    messages: {
      amount: {
        required: "Lütfen tutar giriniz",
        inputPattern: "Tutar sayısal değer olmalıdır",
      },
    },
  });
  if (!form.valid()) {
    return;
  }

  let formData = new FormData(form[0]);

  formData.append("action", "saveTransaction");

    for (var pair of formData.entries()) {
      console.log(pair[0] + ", " + pair[1]);
    }

  fetch("/api/financial/transaction.php", {
    method: "POST",
    body: formData,
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
          transaction.id,
          transaction.date,
          transaction.type_id,
          transaction.sub_type,
          transaction.case_id,
          transaction.amount,
          transaction.description,
          `<div class="dropdown">
            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">İşlem</button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item route-link" data-page="financial/transaction/manage&id=<?php echo $transaction->id ?>" href="#">
                    <i class="ti ti-edit icon me-3"></i> Güncelle
                </a>
                <a class="dropdown-item delete-transaction" data-id="<?php echo $transaction->id ?>" href="#">
                    <i class="ti ti-trash icon me-3"></i> Sil
                </a>
            </div>
        </div>`,
        ])
        .draw(false);
      
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


$(document).on('click', '.transaction_type', function() {
    var type = $(this).val();
     var formData = new FormData();
    formData.append("action", "getSubTypes");
    formData.append("type", type);

    fetch("api/financial/transaction.php", {
        method: "POST",
        body: formData,
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