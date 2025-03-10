function createAjax(dataTableName, formId, url, modalId, successMessage = 'Kaydetme Başarılı') {
    var formElement = document.getElementById(formId);
    var formData = new FormData(formElement);

    // CKEditor içeriğini FormData'ya ekle
    if (typeof CKEDITOR !== 'undefined') {
        for (var instance in CKEDITOR.instances) {
            if (document.getElementById(instance)) { // Eğer formda bu alan varsa
                formData.set(instance, CKEDITOR.instances[instance].getData());
            }
        }
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        headers: {'X-CSRF-TOKEN': csrfToken},
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                html: successMessage
            }).then(ok => {
                window.location.reload();
            });

            // Formu sıfırla
            formElement.reset();

            // CKEditor içeriğini temizle
            if (typeof CKEDITOR !== 'undefined') {
                for (var instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].setData('');
                }
            }

            // Modal'ı kapat
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
    var formElement = document.getElementById(formId);
    var formData = new FormData(formElement);

    // CKEditor içeriğini FormData'ya ekle
    if (typeof CKEDITOR !== 'undefined') {
        for (var instance in CKEDITOR.instances) {
            if (document.getElementById(instance)) {
                formData.set(instance, CKEDITOR.instances[instance].getData());
            }
        }
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        headers: {'X-CSRF-TOKEN': csrfToken},
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                html: successMessage
            }).then(ok => {
                window.location.reload();
            });

            // Formu sıfırla
            formElement.reset();

            // CKEditor içeriğini temizle
            if (typeof CKEDITOR !== 'undefined') {
                for (var instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].setData('');
                }
            }

            // Modal'ı kapat
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
