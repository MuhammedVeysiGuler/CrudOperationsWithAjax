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
