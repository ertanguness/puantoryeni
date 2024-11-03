$(document).on("click", "#send-ticket", function () {
  var form = $("#supportTicketForm");
  let formData = new FormData(form[0]);

  for (var pair of formData.entries()) {
    console.log(pair[0] + ", " + pair[1]);
  }

  fetch("api/supports/tickets.php", {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      //   console.log(data);
      title = data.status == "success" ? "Başarılı!" : "Hata!";
      swal.fire({
        title: title,
        text: data.message,
        icon: data.status,
      
      }).then(() => {
        if (data.status == "success") { 
          window.location.reload();
        }
      });
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});
