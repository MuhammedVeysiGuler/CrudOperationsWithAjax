<?php

namespace Muhammedveysiguler\DynamicDatatable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Muhammedveysiguler\DynamicDatatable\Traits\DataTableTrait;
use Yajra\DataTables\DataTables;

class DynamicDatatableController extends Controller
{
    use DataTableTrait;

    /**
     * Render the datatable view
     *
     * @param array $columns
     * @param string $fetchUrl
     * @param string $title
     * @param array $options
     * @param array $filters
     * @param bool $plusButton
     * @param string|null $plusParentIdKey
     * @return \Illuminate\Contracts\View\View
     */
    public function render(
        string $tableId,
        string $dataTableName,
        array $columns,
        string $fetchUrl,
        string $title = '',
        array $options = [],
        array $filters = [],
        bool $plusButton = false,
        ?string $plusParentIdKey = null
    ) {

        $defaultOptions = config('babasultan23-dynamic-datatable.options', []);
        $options = array_merge($defaultOptions, $options);

        return view(config('babasultan23-dynamic-datatable.view'), compact(
            'columns',
            'fetchUrl',
            'title',
            'options',
            'filters',
            'plusButton',
            'plusParentIdKey',
            'tableId',
            'dataTableName'
        ));
    }

    /**
     * Get order mapping for datatable
     *
     * @return array
     */
    protected function getOrderMappingDataTable()
    {
        return [];
    }

    /**
     * Get search mapping for datatable
     *
     * @return array
     */
    protected function getSearchMappingDataTable()
    {
        return [];
    }

    /**
     * Get action buttons for datatable
     *
     * @param mixed $row
     * @return string
     */
    protected function getActionButtonsDataTable($row)
    {
        return '';
    }

    protected function formatDataTableResponse($query, $totalRecords, $filteredRecords)
    {
        return DataTables::of($query)
            ->with([
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ])
            ->addColumn('actions', function ($row) {
                return $this->getActionButtonsDataTable($row);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
