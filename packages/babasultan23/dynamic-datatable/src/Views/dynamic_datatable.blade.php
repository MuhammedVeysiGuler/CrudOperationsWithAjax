<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="page-header">
        <h2 class="pageheader-title">{{ $title }}</h2>
    </div>
</div>

{!! isset($filters['html']) && !empty($filters['html']) ? $filters['html'] : '' !!}

<div class="table-container" style="position: relative;">
    <table id="{{ $tableId }}" class="{{ $defaultClasses['table'] }}" style="width:100%">
        @if($manuelSearch)
            <div class="col-md-4 manuel-search" style="position: absolute;z-index: 9999; right: 0; top: 0; display: flex; align-items: center; justify-content: flex-end;">
                <label for="dateFilter" class="form-label">Ara:</label>
                <input type="text" id="manuel-search-{{ $tableId }}" placeholder="" class="form-control" style="width:170px">
            </div>
        @endif
        <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column['title'] }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column['title'] }}</th>
            @endforeach
        </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var {{$dataTableName}} = $('#{{ $tableId }}').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ $fetchUrl }}',
                data: function (d) {
                    @if($manuelSearch)
                    d.manuel_search = $('#manuel-search-{{ $tableId }}').val();
                    @endif
                    // If filterData exists and is not empty, inject it
                    {!! isset($filters['js']['filterData']) && !empty($filters['js']['filterData']) ? $filters['js']['filterData'] : '{}' !!}
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
            @if(isset($plusButton) && $plusButton)
            rowCallback: function (row, data) {
                // Remove any existing child row when redrawing
                if ({{$dataTableName}}.row(row).child.isShown()) {
                    {{$dataTableName}}.row(row).child.hide();
                    $(row).removeClass('shown');
                }
            },
            @endif
            ...{!! json_encode($mergedOptions ?? []) !!}
        });

        @if(isset($filters['js']['filterElements']) && count($filters['js']['filterElements']) > 0)
        @foreach($filters['js']['filterElements'] as $element)
        $('#{{ $element }}').change(function () {
            {{$dataTableName}}.draw();
        });
        @endforeach
        @endif

        @if(isset($plusButton) && $plusButton)
        function toggleButtonState(button, isOpen) {
            var icon = button.find('i');
            if (isOpen) {
                button.removeClass('btn-success').addClass('btn-danger');
                icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
            } else {
                button.removeClass('btn-danger').addClass('btn-success');
                icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
            }
        }

        function createSubTable(parentTable, parentRow, level, parentIdKey) {
            var tr = $(parentRow);
            var row = parentTable.row(tr);
            var rowData = row.data();
            var button = tr.find('.sub-menu-button');

            if (row.child.isShown()) {
                // Sadece tıklanan menüyü kapat
                row.child.hide();
                tr.removeClass('shown');
                toggleButtonState(button, false);

                // Sadece bu alt tablonun event listener'larını temizle
                var childTableId = tr.next().find('table').attr('id');
                if (childTableId) {
                    $('#' + childTableId).DataTable().destroy();
                }
            } else {
                // Benzersiz bir ID oluştur
                var timestamp = new Date().getTime();
                var randomNum = Math.floor(Math.random() * 1000);
                var childTableId = '{{ $tableId }}_child_' + level + '_' + timestamp + '_' + randomNum;

                // Alt tablo container'ı oluştur
                var childTableHtml = '<div class="{{ $defaultClasses['container'] }}" style="margin-left: ' + (level * 20) + 'px">' +
                    '<table id="' + childTableId + '" class="{{ $defaultClasses['table'] }}" style="width:100%">' +
                    '<thead><tr>';

                @foreach($columns as $column)
                    childTableHtml += '<th>{{ $column['title'] }}</th>';
                @endforeach

                    childTableHtml += '</tr></thead></table></div>';

                // Satırı aç
                row.child(childTableHtml).show();
                tr.addClass('shown');
                toggleButtonState(button, true);

                // Alt DataTable'ı başlat
                var childTable = $('#' + childTableId).DataTable({
                    ajax: {
                        url: '{{ $fetchUrl }}',
                        data: function (d) {
                            d[parentIdKey] = rowData.id; // Dinamik parent ID key kullanımı
                            {!! isset($filters['js']['filterData']) && !empty($filters['js']['filterData']) ? $filters['js']['filterData'] : '' !!}
                        }
                    },
                    columns: [
                            @foreach($columns as $column)
                        {
                            data: '{{ $column['data'] }}',
                            name: '{{ $column['data'] }}',
                            orderable: {{ $column['orderable'] ?? 'true' }}
                        }@if(!$loop->last),@endif
                        @endforeach
                    ],
                    ...{!! json_encode($mergedOptions ?? []) !!}
                });

                // Alt tabloya click handler ekle
                $('#' + childTableId + ' tbody').on('click', '.sub-menu-button', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Event'in üst elementlere yayılmasını engelle
                    createSubTable(childTable, $(this).closest('tr'), level + 1, parentIdKey);
                });
            }
        }

        // Ana tabloya click handler ekle
        $('#{{ $tableId }} tbody').on('click', '.sub-menu-button', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Event'in üst elementlere yayılmasını engelle
            createSubTable({{$dataTableName}}, $(this).closest('tr'), 1, '{{ $plusParentIdKey }}'); // Dinamik key
        });
        @endif

        @if($manuelSearch)
        // Manuel search functionality

        $('#manuel-search-{{ $tableId }}').on('keyup', function(){
            {{$dataTableName}}.draw();
        });

        @endif
    });
</script>

<script>
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>

<style>
    .child-table-container {
        background-color: #f8f9fa;
        border-radius: 4px;
        margin: 10px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sub-menu-button {
        transition: all 0.3s ease;
    }

    .sub-menu-button i {
        transition: all 0.3s ease;
    }
</style>
