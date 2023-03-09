@extends("panel.layout.app")
@section("content")
    <div id="forPdf">


        <div class="modal fade bd-example-modal-lg" style="overflow: scroll" id="add-modal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #E5E8E8;">
                        <h5 class="modal-title" style="font-weight: bold; font-size: 25px !important; ">Kayıt Ol</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #F8F9F9;">
                        <form id="create_sing_in">

                            <div class="row mt-3 mb-4">
                                <div class="form-group mb-4 col-12">

                                    <label class="mb-1" for="name" style="text-decoration: underline;">Adınız : </label>
                                    <input type="text" name="name" id="name" class="form-control" required>

                                    <label class="mb-1" for="surname" style="text-decoration: underline;">Soyadınız
                                        : </label>
                                    <input type="text" name="surname" id="surname" class="form-control" required>

                                    <label class="mb-1" for="city" style="text-decoration: underline;">Şehir : </label>
                                    <input type="text" name="city" id="city" class="form-control" required>

                                    <label class="mb-1" for="mail" style="text-decoration: underline;">Mail Adresiniz
                                        : </label>
                                    <input type="email" name="mail" id="mail" class="form-control" required>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #E5E8E8;">
                        <button type="button" onclick="createSignIn()" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" style="overflow: scroll" id="update_modal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #E5E8E8;">
                        <h5 class="modal-title" style="font-weight: bold; font-size: 25px !important; ">Kayıt
                            Güncelle</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #F8F9F9; padding: 30px;">
                        <form id="update_sing_in" method="post">
                            @csrf
                            <div class="row mt-3 mb-4">
                                <div class="form-group mb-4 col-12">

                                    <label class="mb-1" for="name" style="text-decoration: underline;">Adınız : </label>
                                    <input type="text" name="name" id="nameUpdate" class="form-control" required>

                                    <label class="mb-1" for="surname" style="text-decoration: underline;">Soyadınız
                                        : </label>
                                    <input type="text" name="surname" id="surnameUpdate" class="form-control" required>

                                    <label class="mb-1" for="city" style="text-decoration: underline;">Şehir : </label>
                                    <input type="text" name="city" id="cityUpdate" class="form-control" required>

                                    <label class="mb-1" for="mail" style="text-decoration: underline;">Mail Adresiniz
                                        : </label>
                                    <input type="email" name="mail" id="mailUpdate" class="form-control" required>

                                </div>

                            </div>

                            <input type="hidden" name="updateId" id="updateId">

                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #E5E8E8;">
                        <button type="button" onclick="updateSignInPost()" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pdf container" style="margin: auto;margin-top: 50px;">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Kayıt Olanlar</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card p-5">
                        <table id="signIn-table" class="display nowrap dataTable cell-border" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Adı - Soyadı</th>
                                <th>Mail Adresi</th>
                                <th>Bulunduğu İl</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Adı - Soyadı</th>
                                <th>Mail Adresi</th>
                                <th>Bulunduğu İl</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="button" class="btn btn-info float-left" onclick="openModal()"
                                data-bs-toggle="#add-modal"><i class="fas fa-plus"></i>Kayıt
                            Oluştur
                        </button>
                        <input type="button" id="rep" value="Pdf Yap" class="btn btn-info btn_print">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- butonlar için gerekli olan kütüphaneler --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                }
            });
        });

        function openModal() {
            $('#add-modal').modal("toggle");
        }

        function createSignIn() {
            var formData = new FormData(document.getElementById('create_sing_in'));
            $.ajax({
                type: 'POST',
                url: '{{route('sign_in.create')}}',
                data: formData,
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}} "},
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı',
                        html: 'Kayıt Oluşturuldu!'
                    });
                    var elements = document.getElementById("create_sing_in").elements;
                    for (var i = 0, element; element = elements[i++];) {
                        element.value = "";
                    }
                    $('#add-modal').modal("toggle");
                    dataTable.ajax.reload();
                },
                error: function (data) {
                    var errors = '';
                    for (datas in data.responseJSON.errors) {
                        errors += data.responseJSON.errors[datas] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Başarısız',
                        html: 'Bilinmeyen bir hata oluştu.\n' + errors,
                    });
                }
            });
        }

        function deleteSignIn(id) {
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
                        headers: {'X-CSRF-TOKEN': "{{csrf_token()}} "},
                        url: '{!! route('sign_in.delete') !!}',
                        data: {
                            id: id
                        },
                        dataType: "json",
                        success: function () {
                            Swal.fire({
                                icon: "success",
                                title: "Başarılı",
                                showConfirmButton: true,
                                confirmButtonText: "Tamam"
                            });
                            dataTable.ajax.reload();
                        },
                        error: function () {
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

        function updateSignIn(id) {
            var name = $('#nameUpdate');
            var surname = $('#surnameUpdate');
            var city = $('#cityUpdate');
            var mail = $('#mailUpdate');
            $.ajax({
                type: 'GET',
                url: '{{route('sign_in.get')}}',
                data: {id: id},
                success: function (data) {

                    $('#updateId').val(id);
                    name.val(data.name);
                    surname.val(data.surname);
                    city.val(data.city);
                    mail.val(data.mail);
                    $('#update_modal').modal("toggle");

                },
                error: function (data) {
                    var errors = '';
                    for (datas in data.responseJSON.errors) {
                        errors += data.responseJSON.errors[datas] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Başarısız',

                        html: 'Bilinmeyen bir hata oluştu.\n' + errors,
                    });
                }
            });
        }

        function updateSignInPost() {
            var formData = new FormData(document.getElementById('update_sing_in'));
            $.ajax({
                type: 'POST',
                url: '{{route('sign_in.update')}}',
                dataType: "json",
                data: formData,
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}} "},
                contentType: false,
                cache: false,
                processData: false,
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı',
                        html: 'Güncelleme Başarılı!'
                    });

                    var elements = document.getElementById("update_sing_in").elements;

                    for (var i = 0, element; element = elements[i++];) {
                        element.value = "";
                    }

                    $('#update_modal').modal("toggle");
                    dataTable.ajax.reload(null, false);
                },
                error: function (data) {
                    var errors = '';
                    for (datas in data.responseJSON.errors) {
                        errors += data.responseJSON.errors[datas] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Başarısız',
                        html: 'Bilinmeyen bir hata oluştu.\n' + errors,
                    });
                }
            });
        }

        $('table#signIn-table tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="' + title + '"/>')
        })

        var dataTable = null;
        dataTable = $('#signIn-table').DataTable({
            initComplete: function () {
                this.api().columns().every(function () {
                    var that = this;
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                    })
                })
            },

            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Turkish.json'
            },
            order: [
                [0, 'ASC']
            ],
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            // lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: '{!! route('sign_in.fetch') !!}',
                data: function (data) {
                    data.veri1 = "veri_1";
                    data.veri2 = "veri_2";
                }
            },

            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-default btn-sm',
                    text: 'Butonları Kaldır',
                    buttons: [
                        {
                            text: 'Güncelle Modal',
                            action: function (e, dt, node, config) {
                                dt.column(-3).visible(!dt.column(-3).visible());
                            }
                        },
                        {
                            text: 'Güncelle Page',
                            action: function (e, dt, node, config) {
                                dt.column(-2).visible(!dt.column(-2).visible());
                            }
                        },
                        {
                            text: 'Sil',
                            action: function (e, dt, node, config) {
                                dt.column(-1).visible(!dt.column(-1).visible());
                            }
                        }
                    ]
                },
                {
                    extend: 'collection',
                    className: 'btn btn-default btn-sm',
                    text: 'Dışarı Aktar',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fa fa-files-o"></i> Kopyala',
                            titleAttr: 'Copy',
                            className: 'btn btn-default btn-sm'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fa fa-files-o"></i> CSV',
                            titleAttr: 'CSV',
                            className: 'btn btn-default btn-sm',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fa fa-files-o"></i> Excel',
                            titleAttr: 'Excel',
                            className: 'btn btn-default btn-sm',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf-o"></i> PDF',
                            titleAttr: 'PDF',
                            className: 'btn btn-default btn-sm',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ]
                },

            ],

            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'city'},
                {data: 'updateModal'},
                {data: 'updatePage'},
                {data: 'delete'},
            ]
        });
    </script>



    <script type="text/javascript">

        $(document).ready(function ($) {
            $(document).on('click', '.btn_print', function (event) {
                event.preventDefault();
                var element = document.getElementById('forPdf');   //pdfin olacağı divin id'si
                var opt = {
                    margin: 1,
                    filename: "Pdf_" + new Date().toJSON().slice(0, 10) + "_" + Math.random() * 23 + ".pdf",
                    image: {type: 'jpeg', quality: 0.98},
                    html2canvas: {scale: 2},
                    jsPDF: {unit: 'mm', format: 'a4', orientation: 'l'}
                };

                var mainPdf = html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdfObj) {
                    var formData = pdfObj.output('datauristring')  //base64 olan pdfin datası
                    var fileName = opt.filename
                    $.ajax({
                        method: "POST",
                        url: '{{route('pdf')}}',
                        data: {data: formData, filename: fileName},
                    }).success(function (data) {
                        html2pdf().set(opt).from(element).save();  //kaydeder
                    }).error(function (e) {
                        console.log("Hata" + e)     //hata basar
                    });
                });
            });
        });
    </script>

@endsection

@section('scripts')

@endsection
