$(document).on("click", ".add-payment", function () {
    let personel_id = $(this).data("id");
    let personel_name = $(this).closest("tr").find("td:eq(1)").text();
    $("#person_id_payment").val(personel_id);
    $("#person_name_payment").text(personel_name);
});

$(document).on('click', '#payment_addButton', function () {
    var form = $('#payment_modalForm');

    form.validate({
        rules: {
            payment_amount: {
                required: true,
                number: true
            },
            payment_type: {
                required: true
            },
        },
        messages: {
            payment_amount: {
                required: "Lütfen bir miktar giriniz.",
                number: "Lütfen geçerli bir miktar giriniz."
            },
            payment_type: {
                required: "Lütfen ödeme adını giriniz."
            }
        }
    });

    if (!form.valid()) {
        return;
    }

    var formData = new FormData(form[0]);

    formData.append("action", "savePayment");

    for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }

    fetch("api/bordro/payment.php", {
        method: "POST",
        body: formData
    }).then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                $('#payment-modal').modal('hide');
                form.trigger("reset");
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Ödeme başarıyla eklendi.'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Ödeme eklenirken bir hata oluştu.'
                });
            }
        })
});