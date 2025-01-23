function deleteAjax(dataTableName,formId, url, modalId, successMessage = 'Silme İşlemi Başarılı',deleteDataId) {
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
                    Swal.fire({
                        icon: "error",
                        title: "Hata!",
                        html: "<div id=\"validation-errors\"></div>",
                        showConfirmButton: true,
                        confirmButtonText: "Tamam"
                    });
                    $.each(data.responseJSON.errors, function (key, value) {
                        $('#validation-errors').append('<div class="alert alert-danger">' + value + '</div>');
                    });
                }
            });
        }
    });
}
