if ($(".datatable").length > 0) {
  $(".datatable:not(#puantajTable)").DataTable({
    autoWidth: false,
    language: {
      url: "src/tr.json",
    },
  });

  $("#yscTable").DataTable({});

  $("#puantajTable").DataTable({
    ordering: false,
    language: {
      url: "src/tr.json",
    },
  });
}

if ($(".select2").length > 0) {
  $(".select2").select2();

  $("#products").select2({
    dropdownParent: $(".modal"),
  });
  $(".modal-select").select2({
    dropdownParent: $(".modal"),
  });
  $("#amount_money").select2({
    dropdownParent: $(".modal"),
  });
  $("#wage_cut_month, #wage_cut_year,#income_month,#income_year").select2({
    dropdownParent: $(".modal"),
  });
}

if ($(".flatpickr").length > 0) {
  $(".flatpickr").flatpickr({
    dateFormat: "d.m.Y",
    locale: "tr", // locale for this instance only
  });
}

if ($(".summernote").length > 0) {
  $(".summernote").summernote();
}
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}

$(document).on("click", ".route-link", function () {
  var page = $(this).data("page");
  var link = "index.php?p=" + page;

  window.location = link;
});
if ($(".select2").length > 0) {
  $(".select2.islem").select2({
    tags: true,
  });
}

function deleteRecord(
  button = this,
  action = null,
  confirmMessage = "Kayıt silinecektir!",
  url = "/api/ajax.php"
) {
  // Butonun bulunduğu satırın referansını al
  var row = $(button).closest("tr");

  //Tablo adı butonun içinde bulunduğu tablo
  var tableName = $(button).closest("table")[0].id;
  var table = $("#" + tableName).DataTable();

  var tableRow = table.row(row);

  var id = $(button).data("id");

  //formData objesi oluştur
  const formData = new FormData();
  //formData objesine action ve id elemanlarını ekle
  formData.append("action", action);
  formData.append("id", id);
  // formData.append("csrf_token", csrf_token);

  // console.log(url);

  AlertConfirm(confirmMessage).then((result) => {
    fetch(url, {
      method: "POST",
      body: formData,
    })
      //Gelen yanıtı json'a çevir
      .then((response) => response.json())

      //Sonuc olumlu ise success toast mesajı göster
      .then((data) => {
        Swal.fire({
          title: "Başarılı!",
          text: data.message,
          icon: "success",
        }).then((result) => {
          if (result.isConfirmed) {
            tableRow.remove().draw(false);
          }
        });
        // createToast("success", data.message);
      })

      //Sonuc olumsuz ise error toast mesajı göster
      .catch((error) => alert("Error deleting : " + error));
  });
}
function AlertConfirm(confirmMessage = "Emin misiniz?") {
  return new Promise((resolve, reject) => {
    Swal.fire({
      title: "Emin misiniz?",
      text: confirmMessage,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Evet, Sil!",
    }).then((result) => {
      if (result.isConfirmed) {
        resolve(true); // Kullanıcı onayladı, işlemi devam ettir
      } else {
        reject(false); // Kullanıcı onaylamadı, işlemi durdur
      }
    });
  });
}
$(document).on("change", "#myFirm", function () {
  var page = new URLSearchParams(window.location.search).get("p");
  window.location = "set-session.php?p=" + page + "&firm_id=" + $(this).val();
});

function fadeOut(element, duration) {
  var op = 1; // Opaklık başlangıç değeri
  var interval = 50; // Milisaniye cinsinden aralık
  var delta = interval / duration; // Her adımda azaltılacak opaklık miktarı

  function reduceOpacity() {
    op -= delta;
    if (op <= 0) {
      op = 0;
      element.style.display = "none"; // Elementi gizle
      clearInterval(fading); // Animasyonu durdur
    }
    element.style.opacity = op;
  }

  var fading = setInterval(reduceOpacity, interval);
}
