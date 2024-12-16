$(document).on("click", "#savePerson", function () {
  var form = $("#personForm");
  jQuery.validator.addMethod(
    "money",
    function (value, element) {
      var isValidMoney = /^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/.test(value);
      return this.optional(element) || isValidMoney;
    },
    "Lütfen geçerli bir para birimi giriniz"
  );
  form.validate({
    rules: {
      full_name: {
        required: true,
        minlength: 3,
        maxlength: 50,
        number: false,
      },
      kimlik_no: {
        required: true,
        minlength: 11,
        maxlength: 11,
        number: true,
      },
      job_start_date: {
        required: true,
      },
      daily_wages: {
        required: true,
        money: true,
      },
    },
    messages: {
      full_name: {
        required: "Lütfen personel adını giriniz",
        minlength: "Ad-Soyad en az 3 karakter olmalıdır",
        maxlength: "Ad-Soyad en fazla 50 karakter olmalıdır",
        number: "Ad-Soyadda sayısal değer bulunamaz",
      },
      kimlik_no: {
        required: "Lütfen kimlik numarasını giriniz",
        minlength: "Kimlik numarası 11 karakter olmalıdır",
        maxlength: "Kimlik numarası 11 karakter olmalıdır",
        number: "Kimlik numarası sayısal değer olmalıdır",
      },
      job_start_date: {
        required: "Lütfen işe başlama tarihini giriniz",
        date: "Lütfen geçerli bir tarih giriniz",
      },
      daily_wages: {
        required: "Ücret alanı zorunludur",
      },
    },
  });
  if (!form.valid()) return false;

  var formData = new FormData(form[0]);
  formData.append("action", "savePerson");

  fetch("api/persons/person.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      //console.log(data);
      if (data.status == "success") {
        title = "Başarılı!";
        $("#person_id").val(data.lastid);
      } else {
        title = "Hata!";
      }
      swal.fire({
        title: title,
        text: data.message,
        icon: data.status,
      });
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});

$(document).on("click", ".delete-person", function () {
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deletePerson";
  let confirmMessage = "Personel silinecektir!";
  let url = "/api/persons/person.php";

  deleteRecord(this, action, confirmMessage, url);
  // deleteRecord(this, action, confirmMessage, url);
});

$('input[name="kimlik_no"]').keypress(function (e) {
  if (this.value.length >= 11) {
    return false;
  }
  if (e.which < 48 || e.which > 57) {
    return false;
  }
});


$(document).on('click', '.wage_type', function () {
  if ($(this).attr('id') === 'blue_collar') {
    $('#wage_type_label').text('Günlük Ücreti');

  } else if ($(this).attr('id') === 'white_collar') {
    $('#wage_type_label').text('Aylık Maaş');
  }

});


$(document).on("click", ".delete-payment", async function () {
  let type_name = $(this).closest("tr").find("td:eq(2)").text();
  let type = $(this).closest("tr").find("td:eq(5)").text();
  let person_id = $("#person_id").val();
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deletePayment";
  let confirmMessage = type_name + " silinecektir!";
  let url = "/api/persons/person.php?person_id=" + person_id  + "&type=" + type;

  const result = await deleteRecordByReturn(this, action, confirmMessage, url);

  let income_expense = result.income_expense;

  let total_income = income_expense.total_income;
  let total_expense = income_expense.total_expense;
  let total_payment = income_expense.total_payment;
  let balance = income_expense.balance;

  // console.log(total_income + " " + total_payment + " " + balance);
  

  $("#total_payment").text(total_payment);
  $("#total_income").text(total_income);
  $("#total_expense").text(total_expense);
  $("#balance").text(balance);
  

});
