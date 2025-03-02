function createAjax(dataTableName, formId, url, modalId, successMessage = 'Kaydetme Başarılı') {
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
                errors += data.responseJSON.errors[datas].join('<br>') + '<br>';
            }
            Swal.fire({
                icon: 'error',
                title: 'Başarısız',
                html: errors,
            });
        }
    });
}


function deleteAjax(dataTableName, formId, url, modalId, successMessage = 'Silme İşlemi Başarılı', deleteDataId) {
    Swal.fire({
        icon: "warning",
        title: "Emin misiniz?",
        html: "Silmek istediğinize emin misiniz?",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Onayla",
        cancelButtonText: "İptal",
        cancelButtonColor: "#e30d0d"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': csrfToken},
                url: url,
                data: {
                    id: deleteDataId
                },
                dataType: "json",
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Başarılı",
                        html: successMessage,
                        showConfirmButton: true,
                        confirmButtonText: "Tamam"
                    }).then(ok => {
                        window.location.reload()
                    });

                    // var table = $('#' + dataTableName);
                    // table.ajax.reload();
                },
                error: function (data) {
                    var errors = '';

                    for (datas in data.responseJSON.errors) {
                        errors += data.responseJSON.errors[datas].join('<br>') + '<br>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Başarısız',
                        html: errors,
                    });
                }
            });
        }
    });
}


function getAjaxData(dataTableName, url, modalId, fieldMapping, dataId = null) {
    var data = {};
    if (dataId !== null) {
        data.id = dataId; // ID parametresi varsa ekle
    }

    $.ajax({
        type: 'GET',
        url: url,
        data: data, // ID parametresini AJAX isteğiyle gönder
        dataType: "json", // Beklenen veri tipi JSON
        success: function (response) {
            // Kullanıcı tarafından belirtilen mapping'e göre input alanlarını doldur
            for (var inputId in fieldMapping) {
                if (fieldMapping.hasOwnProperty(inputId)) {
                    var responseKey = fieldMapping[inputId];
                    var inputField = $('#' + inputId); // Input alanını ID'ye göre seç
                    if (inputField.length && response.hasOwnProperty(responseKey)) { // Input alanı modalda varsa ve response'da key varsa değeri ayarla
                        inputField.val(response[responseKey]);
                    }
                }
            }
            $(modalId).modal("toggle"); // Modalı aç
        },
        error: function (data) {
            var errors = '';

            for (datas in data.responseJSON.errors) {
                errors += data.responseJSON.errors[datas].join('<br>') + '<br>';
            }
            Swal.fire({
                icon: 'error',
                title: 'Başarısız',
                html: errors,
            });
        }
    });
}


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
                errors += data.responseJSON.errors[datas].join('<br>') + '<br>';
            }
            Swal.fire({
                icon: 'error',
                title: 'Başarısız',
                html: errors,
            });
        }
    });


}
