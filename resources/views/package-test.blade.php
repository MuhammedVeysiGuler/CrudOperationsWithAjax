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
                    <form id="create_student">

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
                                <input type="email" name="email" id="mail" class="form-control" required>

                                <label class="mb-1" for="mail" style="text-decoration: underline;">Ders Seçiniz
                                    : </label>
                                <select name="lesson_id" id="lesson_id" class="form-control" required>
                                    <option value="" disabled selected>Seçiniz</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background-color: #E5E8E8;">
                    <button type="button" onclick="createStudent()" class="btn btn-primary">Kaydet</button>
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
                    <form id="update_student" method="post">
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
                                <input type="email" name="email" id="mailUpdate" class="form-control" required>

                                <label class="mb-1" for="mail" style="text-decoration: underline;">Ders Seçiniz
                                    : </label>
                                <select name="lesson_id" id="lesson_idUpdate" class="form-control" required>
                                    <option value="" disabled selected>Seçiniz</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <input type="hidden" name="updateId" id="updateId">

                    </form>
                </div>
                <div class="modal-footer" style="background-color: #E5E8E8;">
                    <button type="button" onclick="updateStudentPost()" class="btn btn-primary">Kaydet</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        {!! $dataTable !!}
        <div class="card-footer clearfix">
            <button type="button" class="btn btn-info float-left" onclick="openModal()"
                    data-bs-toggle="#add-modal"><i class="fas fa-plus"></i>Kayıt Oluştur
            </button>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <script>

        function openModal() {
            $('#add-modal').modal("toggle");
        }

        function createStudent() {
            createAjax(
                "studentDatatable",              // DataTable Name
                "create_student",                // formId
                "{{ route('student.create') }}", // URL
                "#add-modal",                    // modalId
                "Kayıt başarıyla oluşturuldu!"   // successMessage (optional)
            );
        }

        function deleteStudent(id) {
            deleteAjax(
                "studentDatatable",              // DataTable Name
                "",                              // formId
                "{{ route('student.delete') }}", // URL
                "",                              // modalId
                "Kayıt başarıyla silindi!",      // successMessage (optional)
                id                               // Silinecek Datanın Id değeri
            );
        }

        function updateStudent(id) {
            var fieldMapping = { // Input ID - Response Key mapping
                'nameUpdate': 'name',
                'surnameUpdate': 'surname',
                'cityUpdate': 'city',
                'mailUpdate': 'email',
                'updateId': 'updateId',
                'lesson_idUpdate': 'lesson_id'
            };

            getAjaxData(
                "studentDatatable",              // DataTable Name (opsiyonel)
                "{{ route('student.get') }}",    // URL
                "#update_modal",                 // modalId
                fieldMapping,                    // fieldMapping parametresini gönderin
                id                               // Veri ID'si
            );
        }

        function updateStudentPost() {
            updateAjax(
                "studentDatatable",              // DataTable Name
                "update_student",                // formId
                "{{ route('student.update') }}", // URL
                "#update_modal",                 // modalId
                "Kayıt başarıyla güncellendi!"   // successMessage (optional)
            );
        }
    </script>

    {{--    FİLTRELEME JS    --}}
    <script type="text/javascript">
        var cities = @json($cities);

        $(document).ready(function () {
            if (cities && cities.length > 0) {
                let citySelect = $('#cityFilter');

                // Dinamik şehir seçeneklerini oluştur
                cities.forEach(function (city) {
                    citySelect.append('<option value="' + city + '">' + city + '</option>');
                });
            }
        });
    </script>




@endsection
