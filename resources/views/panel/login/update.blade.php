@extends("panel.layout.app")
@section("content")

@endsection
<form id="update_sing_in" method="post">
    @csrf
    <div class="row mt-3 mb-4">
        <div class="form-group mb-4 col-12">

            <label class="mb-1" for="name" style="text-decoration: underline;">Adınız : </label>
            <input type="text" name="name" id="nameUpdate" value="{{$signIn->name}}" class="form-control" required>

            <label class="mb-1" for="surname" style="text-decoration: underline;">Soyadınız
                : </label>
            <input type="text" name="surname" id="surnameUpdate" value="{{$signIn->surname}}" class="form-control" required>

            <label class="mb-1" for="city" style="text-decoration: underline;">Şehir : </label>
            <input type="text" name="city" id="cityUpdate" value="{{$signIn->city}}" class="form-control" required>

            <label class="mb-1" for="mail" style="text-decoration: underline;">Mail Adresiniz
                : </label>
            <input type="email" name="mail" id="mailUpdate" value="{{$signIn->email}}" class="form-control" required>

        </div>

    </div>

</form>

    <button type="button" onclick="updateSignInPost()" class="btn btn-primary">Kaydet</button>
@section('scripts')
    <script>
        function updateSignInPost() {
            var id = {{$id}};
            var formData = new FormData(document.getElementById('update_sing_in'));
            formData.append("updateId",id)
            $.ajax({
                type: 'POST',
                url: '{{route('sign_in.update')}}',
                data: formData,
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}} "},
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı',
                        html: 'Güncelleme Başarılı!'
                    }).then(okay => {
                        if (okay) {
                            window.location.href = "{{route('sign_in.index')}}";
                        }
                    });
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
    </script>
@endsection
