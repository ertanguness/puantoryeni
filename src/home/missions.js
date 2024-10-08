$("#sortable").sortable({
  items: ".header-item",
  update: function (event, ui) {
    var order = $(this).sortable("toArray");
    var form = $("#missionHeadersForm");
    let formData = new FormData();
    formData.append("order", JSON.stringify(order));
    formData.append("action", "updateOrder");

for (var pair of formData.entries()) {
    console.log(pair[0] + ", " + pair[1]);
    }

    fetch("/api/missions/headers.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          // Sıralama güncellendi, DOM'u güncelle
        //   updateOrderInDOM(order);
        console.log("Sıralama güncellendi");
        
        } else {
          console.error("Error updating order:", data.message);
        }
      });
  },
});

$("#sortable").disableSelection();
$("#sortable").on("sortupdate", function(event, ui) {
    var order = $(this).sortable("toArray");
    console.log(order); // Sıralanan öğelerin id'lerini konsola yazdır
});

$(".header-item").sortable({
    connectWith: ".header-item",
    items: ".mission-items",
    update: function (event, ui) {
        var order = $(this).sortable("toArray");
        console.log(order); // Sıralanan öğelerin id'lerini konsola yazdır
    },
    });