<?php

namespace BabaSultan23\DynamicDatatable;

use Illuminate\Support\Traits\Macroable;
use Yajra\DataTables\DataTables;

class DynamicDatatable
{
    use Macroable;

    protected $orderMapping = [];

    protected $searchMapping = [];

    protected $actionButtons = null;

    protected $formatResponse = null;

    protected $manuelSearchCallback = null;

    public function setOrderMapping(array $mapping)
    {
        $this->orderMapping = $mapping;
        return $this;
    }

    public function setSearchMapping(array $mapping)
    {
        $this->searchMapping = $mapping;
        return $this;
    }

    public function setActionButtons(callable $callback)
    {
        $this->actionButtons = $callback;
        return $this;
    }

    public function setFormatResponse(callable $callback)
    {
        $this->formatResponse = $callback;
        return $this;
    }

    public function setManuelSearchCallback(callable $callback)
    {
        $this->manuelSearchCallback = $callback;
        return $this;
    }

    public function getOrderMapping()
    {
        return $this->orderMapping;
    }

    public function getSearchMapping()
    {
        return $this->searchMapping;
    }

    public function getActionButtons($row)
    {
        return $this->actionButtons ? call_user_func($this->actionButtons, $row) : '';
    }

    public function handleManuelSearch($query, $searchValue)
    {
        if ($this->manuelSearchCallback && $searchValue) {
            return call_user_func($this->manuelSearchCallback, $query, $searchValue);
        }
        return $query;
    }

    public function handleDataTableQuery($query, $request)
    {
        $totalRecords = $query->count();
        $filteredRecords = $totalRecords;

        // Manuel Search
        if ($request->manuel_search) {
            $query = $this->handleManuelSearch($query, $request->manuel_search);
            $filteredRecords = $query->count();
        } // Normal Search (only if manuel search is not active)
        else if ($request->search && $request->search['value']) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                foreach ($this->searchMapping as $column => $field) {
                    $q->orWhereRaw("$field LIKE ?", ["%{$searchValue}%"]);
                }
            });
            $filteredRecords = $query->count();
        }

        // Order
        if ($request->order && isset($request->order[0])) {
            $orderColumn = $request->columns[$request->order[0]['column']]['data'];
            $orderDir = $request->order[0]['dir'];

            if (isset($this->orderMapping[$orderColumn])) {
                $query->orderByRaw($this->orderMapping[$orderColumn] . ' ' . $orderDir);
            } else {
                $query->orderBy($orderColumn, $orderDir);
            }
        }

        // Pagination
        if ($request->length != -1) {
            $query->skip($request->start)->take($request->length);
        }

        return [
            'query' => $query,
            'totalRecords' => $totalRecords,
            'filteredRecords' => $filteredRecords
        ];
    }

    public function formatDataTableResponse($query, $totalRecords, $filteredRecords)
    {
        if ($this->formatResponse) {
            return call_user_func($this->formatResponse, $query, $totalRecords, $filteredRecords);
        }

        return DataTables::of($query)
            ->with([
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ])
            ->make(true);
    }

    public function processDataTableRequest($query, $request)
    {
        $result = $this->handleDataTableQuery($query, $request);
        return $this->formatDataTableResponse(
            $result['query'],
            $result['totalRecords'],
            $result['filteredRecords']
        );
    }

    public function render(
        string  $tableId,
        string  $dataTableName,
        array   $columns,
        string  $fetchUrl,
        string  $title = '',
        array   $options = [],
        array   $filters = [],
        bool    $plusButton = false,
        string  $plusParentIdKey = 'parent_id',
        bool    $manuelSearch = false,
        ?string $language = null
    )
    {
        // Merge default options from config with user options
        $defaultOptions = config('babasultan23-dynamic-datatable.options', []);
        $mergedOptions = array_merge($defaultOptions, $options);

        // Manuel search aktif ise default search'ü kapat
        if ($manuelSearch) {
            $mergedOptions['searching'] = false;
        }

        // Dil ayarlarını ekle
        $defaultLanguage = 'tr';
        $selectedLanguage = $language ?? $defaultLanguage;

        // Dil dosyasını yükle
        $languageData = trans('datatable::datatable', [], $selectedLanguage);
        if (!empty($languageData)) {
            $mergedOptions['language'] = $languageData;
        }

        // Get default classes from config
        $defaultClasses = config('babasultan23-dynamic-datatable.classes', [
            'table' => 'display nowrap dataTable cell-border',
            'container' => 'child-table-container p-3',
        ]);

        $viewPath = 'dynamic-datatable::dynamic_datatable';

        return view($viewPath, compact(
            'tableId',
            'dataTableName',
            'columns',
            'fetchUrl',
            'title',
            'mergedOptions',
            'filters',
            'plusButton',
            'plusParentIdKey',
            'defaultClasses',
            'manuelSearch'
        ));
    }
}
