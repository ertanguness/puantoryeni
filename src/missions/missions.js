var form = $("#missionForm");
$(document).on("click", "#saveMission", function () {
  let formData = new FormData(form[0]);

  for (var pair of formData.entries()) {
    console.log(pair[0] + ", " + pair[1]);
  }

  fetch("api/missions/missions.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
        title = data.status =="success" ? "Başarılı!" : "Hata!";
        Swal.fire({ title: title, text: data.message, icon: data.status });
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});
