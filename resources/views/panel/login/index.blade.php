@extends("panel.layout.app")
@section("content")

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

                                <label class="mb-1" for="surname" style="text-decoration: underline;">Soyadınız : </label>
                                <input type="text" name="surname" id="surname" class="form-control" required>

                                <label class="mb-1" for="city" style="text-decoration: underline;">Şehir : </label>
                                <input type="text" name="city" id="city" class="form-control" required>

                                <label class="mb-1" for="mail" style="text-decoration: underline;">Mail Adresiniz : </label>
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
                    <form id="update_sing_in" method="post" >
                        @csrf
                        <div class="row mt-3 mb-4">
                            <div class="form-group mb-4 col-12">

                                <label class="mb-1" for="name" style="text-decoration: underline;">Adınız : </label>
                                <input type="text" name="name" id="nameUpdate" class="form-control" required>

                                <label class="mb-1" for="surname" style="text-decoration: underline;">Soyadınız : </label>
                                <input type="text" name="surname" id="surnameUpdate" class="form-control" required>

                                <label class="mb-1" for="city" style="text-decoration: underline;">Şehir : </label>
                                <input type="text" name="city" id="cityUpdate" class="form-control" required>

                                <label class="mb-1" for="mail" style="text-decoration: underline;">Mail Adresiniz : </label>
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
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <button type="button" class="btn btn-info float-left" onclick="openModal()"
                            data-bs-toggle="#add-modal"><i class="fas fa-plus"></i>Kayıt
                        Oluştur
                    </button>
                </div>
            </div>
        </div>
    </div>

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

        var dataTable = null;
        dataTable = $('#signIn-table').DataTable({
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
            ajax: '{!! route('sign_in.fetch') !!}',
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'city'},
                {data: 'update'},
                {data: 'delete'},
            ]
        });
    </script>



@endsection

@section('scripts')

@endsection
