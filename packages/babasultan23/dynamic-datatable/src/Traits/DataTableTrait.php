<?php

namespace Muhammedveysiguler\DynamicDatatable\Traits;

use Yajra\DataTables\DataTables;

trait DataTableTrait
{
    protected function handleDataTableQuery($query, $request, $length_size = 10)
    {
        // Sıralama
        $order = $request->input('order.0');
        if ($order) {
            $columnIndex = $order['column'];
            $columnName = $request->input("columns.{$columnIndex}.data");
            $columnDirection = $order['dir'];

            // sıralanmaya katılacak alanlar ekstradan varsa
            $orderMapping = $this->getOrderMappingDataTable();
            if (array_key_exists($columnName, $orderMapping)) {
                $query->orderByRaw("{$orderMapping[$columnName]} $columnDirection");
            } elseif ($columnName) {
                $query->orderBy($columnName, $columnDirection);
            }
        }
        $totalRecords = $query->count();

        // Arama
        $search = $request->input('search.value');
        if ($search) {
            $searchMapping = $this->getSearchMappingDataTable();
            $query->where(function ($q) use ($search, $searchMapping) {
                foreach ($searchMapping as $fieldName => $dbColumn) {
                    if (is_numeric($fieldName)) {
                        $q->orWhere($dbColumn, 'LIKE', "%{$search}%");
                    } else {
                        $q->orWhereRaw("{$dbColumn} LIKE ?", ["%{$search}%"]);
                    }
                }
            });
            $filteredRecords = $query->count();
        } else {
            $filteredRecords = $totalRecords;
        }

        // Paginatleme parametreleri
        $start = $request->input('start', 0);
        $length = $request->input('length', $length_size);

        // Paginate Uygula
        $query->skip($start)->take($length);

        return [
            'query' => $query,
            'totalRecords' => $totalRecords,
            'filteredRecords' => $filteredRecords
        ];
    }

    /**
     * Orderlanacak olan kolon adları ve ver tabanındaki karşılıkları
     *
     * @return array
     * Örnek:
     * return [
     *     'full_name' => "CONCAT(name, ' ', surname)",
     *     // Add more mappings as needed
     * ];
     */
    abstract protected function getOrderMappingDataTable();

    /**
     * Aranmasını istediğin tüm kolonlar yazılacak, yazılmayan kolonlar aramaya dahil edilmez.
     * Aranacak olan kolon adları ve ver tabanındaki karşılıkları
     *
     * @return array
     * Örnek:
     * return [
     *      "KOLON-ADI" => "VERİ TABANI KARŞILIĞI"
     *     'full_name' => "CONCAT(name, ' ', surname)",
     *     // Add more mappings as needed
     * ];
     */
    abstract protected function getSearchMappingDataTable();

    /**
     * DataTable yanıtını formatlar.
     *
     * @param $query
     * @param $totalRecords
     * @param $filteredRecords
     * @return mixed
     */
    protected function formatDataTableResponse($query, $totalRecords, $filteredRecords)
    {
        $datatable = DataTables::of($query)
            ->with([
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ]);
        return $datatable->make(true);
    }

    /**
     * DataTable için aksiyon butonlarını alır.
     *
     * @param $row
     * @return string
     */
    abstract protected function getActionButtonsDataTable($row);
}
