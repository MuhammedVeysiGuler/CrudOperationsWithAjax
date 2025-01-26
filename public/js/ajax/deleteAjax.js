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
