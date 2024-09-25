$(document).on("click", "#saveCase", function () {
  var form = $("#caseForm");

  form.validate({
    rules: {
      case_name: {
        required: true,
      },
    },
    messages: {
      case_name: {
        required: "Kasa Adı boş bırakılamaz!",
      },
    },
  });
  if (!form.valid()) return false;

  var formData = new FormData(form[0]);

  fetch("/api/financial/case.php", {
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
    });
});


$(document).on("click", ".delete-case", function () {
  //Tablo adı butonun içinde bulunduğu tablo
  let action = "deleteCase";
  let confirmMessage = "Kasa, tüm hareketleri ile birlikte silinecektir!";
  let url = "/api/financial/case.php";

  deleteRecord(this, action, confirmMessage, url);
});


$(document).on('click', '.default-case', function() {
 
    let case_id=$(this).data('id');
    var formData = new FormData();
    formData.append('case_id',case_id);
    formData.append('action','defaultCase');


    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]); 
    }
    fetch('/api/financial/case.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
    .then(data => {
        console.log(data);
        if(data.status=="success"){
            title="Başarılı!";
        }else{
            title="Hata!";
        }
        Swal.fire({
            title: title,
            text: data.message,
            icon: data.status,
            confirmButtonText: 'Tamam'
        });
    });

});