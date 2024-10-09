if ($(".datatable").length > 0) {
  $(".datatable:not(#puantajTable)").DataTable({
    autoWidth: false,
    order: false,
    language: {
      url: "src/tr.json",
    },
    layout: {
      bottomStart: 'pageLength',
      bottom2Start: 'info',
      topStart: null,
      topEnd: null
    },
    initComplete: function(settings, json) {
      var api = this.api();
      var tableId = settings.sTableId;
      $('#' + tableId + ' thead').append('<tr class="search-input-row"></tr>');
      
      api.columns().every(function () {
        let column = this;
        let title = column.header().textContent;
        
        if (title != "İşlem") {
          // Create input element
          let input = document.createElement('input');
          input.placeholder = title;
          input.classList.add('form-control');
          input.classList.add('form-control-sm');
          input.setAttribute("autocomplete", "off");

          // Append input element to the new row
          $('#' + tableId + ' .search-input-row').append($('<th class="search">').append(input));

          // Event listener for user input
          $(input).on('keyup change', function() {
            if (column.search() !== this.value) {
              column.search(this.value).draw();
            }
          });
        } else {
          // Eğer "İşlem" sütunuysa, boş bir th ekleyin
          $('#' + tableId + ' .search-input-row').append('<th></th>');
        }
      });
    }
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
  $(".modal .select2").select2({
    dropdownParent: $(".modal"),
  });
  $("#amount_money").select2({
    dropdownParent: $(".modal"),
  });
  $(
    "#wage_cut_month, #wage_cut_year,#income_month, #income_year, #payment_month, #payment_year"
  ).select2({
    dropdownParent: $(".modal"),
  });
}
$(document).ready(function() {
if ($(".summernote").length > 0) {
  var summernoteHeight = $(window).height() * 0.37; // Set height to 30% of window height
  $(".summernote").summernote({
    height: summernoteHeight,
    callbacks: {
      onInit: function() {
        $('.summernote').summernote('height', summernoteHeight);
      }
    }
  });
}
});

if ($(".flatpickr").length > 0) {
  $(".flatpickr").flatpickr({
    dateFormat: "d.m.Y",
    locale: "tr", // locale for this instance only
  });
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


function dtSearchInput(tableId, column, value) {
 

}


//Geri dönüş yapmadan kayıt silme işlemi
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
        // console.log(data);

        if (data.status == "success") {
          title = "Başarılı!";
          icon = "success";
        } else {
          title = "Hata!";
          icon = "error";
        }
        Swal.fire({
          title: title,
          text: data.message,
          icon: icon,
        }).then((result) => {
          if (result.isConfirmed) {
            if (data.status == "success") tableRow.remove().draw(false);
            return data;
          }
        });
        // createToast("success", data.message);
      })

      //Sonuc olumsuz ise error toast mesajı göster
      .catch((error) => alert("Error deleting : " + error));
  });
}

//Geri dönüş yaparak kayıt silme işlemi
async function deleteRecordByReturn(
  button,
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

  const result = await AlertConfirm(confirmMessage);
  if (result) {
    try {
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });
      const data = await response.json();

      let title, icon;
      if (data.status == "success") {
        title = "Başarılı!";
        icon = "success";
      } else {
        title = "Hata!";
        icon = "error";
      }

      await Swal.fire({
        title: title,
        text: data.message,
        icon: icon,
      });

      if (data.status == "success") {
        tableRow.remove().draw(false);
      }

      return data;
    } catch (error) {
      console.error("Error deleting:", error);
      return { status: "error", message: "Bir hata oluştu." };
    }
  }
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

//İl seçildiğinde ilçeleri getir
function getTowns(cityId , targetElement) {
  let formData = new FormData();
  formData.append("city_id", cityId);
  formData.append("action", "getTowns");

  fetch("/api/il-ilce.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      let towns = data.towns;
      $(targetElement).html(towns);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

//Personeli kaydedip kaydetmediğimize bakarız
function checkPersonId(id) {
  if (id == 0) {
    swal.fire({
      title: "Hata",
      icon: "warning",
      text: "Öncelikle personeli kaydetmeniz gerekir!",
    });
    return false;
  }
  return true;
}

