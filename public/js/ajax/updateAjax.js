function updateAjax(dataTableName, formId, url, modalId, successMessage = 'Güncelleme Başarılı') {

    var formData = new FormData(document.getElementById(formId));
    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        headers: {'X-CSRF-TOKEN': csrfToken},  // CSRF token JS'den geliyor
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                html: successMessage
            }).then(ok => {
                window.location.reload()
            });

            // Formu sıfırlama
            var elements = document.getElementById(formId).elements;
            for (var i = 0, element; element = elements[i++];) {
                element.value = "";
            }

            // Modal'ı kapatma
            $(modalId).modal("toggle");

            // DataTable'ı yeniden yükleme
            // var table = $('#' + dataTableName).DataTable();
            // table.reload();
        },
        error: function (data) {
            var errors = '';
            for (datas in data.responseJSON.errors) {
                errors += data.responseJSON.errors[datas] + '\n';
            }
            Swal.fire({
                icon: 'error',
                title: 'Başarısız',
                html: 'Hata.\n' + errors,
            });
        }
    });


}
