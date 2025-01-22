@extends('panel.layout.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <button type="button" class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New {{ $modelName }}
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
            </div>
            <div class="card-body">
                <table id="{{ $tableId }}" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            @foreach($columns as $column)
                                <th>{{ $column['title'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ $fetchUrl }}",
                    type: 'GET',
                },
                columns: {!! json_encode($columns) !!},
            });
        });

        function delete{{ $modelName }}(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ $deleteUrl }}",
                        type: 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Record has been deleted.',
                                'success'
                            );
                            $('#{{ $tableId }}').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function update{{ $modelName }}(id) {
            $.ajax({
                url: "{{ $getUrl }}",
                type: 'GET',
                data: { id: id },
                success: function(response) {
                    // Fill the update modal with response data
                    $('#updateId').val(id);
                    for (let key in response) {
                        $(`#${key}Update`).val(response[key]);
                    }
                    $('#update-modal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }

        function openAddModal() {
            // Check if form exists before trying to reset
            const formId = 'create_' + '{{ strtolower($modelName) }}';
            const form = document.getElementById(formId);
            
            if (form) {
                form.reset();
            }
            
            $('#add-modal').modal('show');
        }
    </script>
@endsection

