<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="page-header">
        <h2 class="pageheader-title">{{ $title }}</h2>
    </div>
</div>
{!! $filters['html'] !!}

<table id="{{ $tableId }}" class="display nowrap dataTable cell-border" style="width:100%">
    <thead>
    <tr>
        @foreach($columns as $column)
            <th>{{ $column['title'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tfoot>
    <tr>
        @foreach($columns as $column)
            <th>{{ $column['title'] }}</th>
        @endforeach
    </tr>
    </tfoot>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        var {{$dataTableName}} = $('#{{ $tableId }}').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ $fetchUrl }}',
                data: function (d) {
                    {!! $filters['js']['filterData'] !!}
                }
            },
            columns: [
                    @foreach($columns as $column)
                {
                    data: '{{ $column['data'] }}',
                    name: '{{ $column['data'] }}',
                    orderable: {{ $column['orderable'] ?? 'true' }}
                }
                @if(!$loop->last),@endif
                @endforeach
            ],
            ...{!! json_encode($options ?? []) !!}
        });

        // Dinamik filter event listeners
        @foreach($filters['js']['filterElements'] as $element)
        $('#{{ $element }}').change(function () {
            {{$dataTableName}}.draw();
        });
        @endforeach
    });
</script>

