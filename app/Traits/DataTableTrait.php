<?php

namespace App\Traits;

use Yajra\DataTables\DataTables;

trait DataTableTrait
{
    protected function handleDataTableQuery($query, $request, $length_size = 10)
    {
        // Sıralama için
        $order = $request->input('order.0');
        if ($order) {
            $columnIndex = $order['column'];
            $columnName = $request->input("columns.{$columnIndex}.data");
            $columnDirection = $order['dir'];

            // sıralanmaya katılacak alanlar ekstradan varsa
            $orderMapping = $this->getOrderMapping();

            if (array_key_exists($columnName, $orderMapping)) {
                $query->orderByRaw("{$orderMapping[$columnName]} $columnDirection");
            } elseif ($columnName) {
                $query->orderBy($columnName, $columnDirection);
            }
        }

        // Filtrelemeden önceki total sayılar
        $totalRecords = $query->count();
        $filteredRecords = $totalRecords;

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
    abstract protected function getOrderMapping();

    /**
     * Aranacak olan kolon adları ve ver tabanındaki karşılıkları
     *
     * @return array
     * Örnek:
     * return [
     *     'full_name' => "CONCAT(name, ' ', surname)",
     *     // Add more mappings as needed
     * ];
     */
    abstract protected function getSearchMapping();

    /**
     * DataTable yanıtını formatlar.
     *
     * @param $query
     * @param $totalRecords
     * @param $filteredRecords
     * @param $query : DataTable'dan dönecek veriler (query).
     * @param $totalRecords : Tablodaki toplam kayıt sayısı (filtreleme yapılmadan önce).
     * @param $filteredRecords : Filtre uygulandıktan sonra toplam kayıt sayısı.
     *
     * Kullanıcılar, bu metod içerisinde DataTable'ı özelleştirebilirler:
     *
     * - `addColumn`: Yeni sütunlar ekleyebilirsiniz.
     * - `filterColumn`: Belirli sütunlar üzerinde filtreleme uygulayabilirsiniz.
     * - `orderColumn`: Sütun sırasını belirleyebilirsiniz.
     * - `rawColumns(['customData'])`: Özel HTML içeriklerini sütunlara doğrudan ekleyebilirsiniz.
     *
     * Sonuçta `make(true)` ile DataTable'ı geri döndürmelisiniz.
     *
     * Örnek:
     * return DataTables::of($query)
     *       ->with([
     *           'recordsTotal' => $totalRecords,
     *           'recordsFiltered' => $filteredRecords,
     *       ])
     *
     *         //eklemek istediğin addColumn,orderColumn,FilterColumn vs vs.    // Bu kısım haricindeki alanlar zorunludur
     *
     *
     *       ->addColumn('action', function ($row) {
     *           return $this->getActionButtons($row);
     *       })
     *      ->rawColumns(['your_custom_columns', 'actions'])
     *       ->make(true);
     * @return mixed
     *
     * Bu metod, DataTable yanıtını özelleştirmek için kullanıcılar tarafından override edilebilir.
     *
     */
    protected function formatDataTableResponse($query, $totalRecords, $filteredRecords)
    {
        return DataTables::of($query)
            ->with([
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ])
            ->addColumn('actions', function ($row) {
                return $this->getActionButtons($row);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * DataTable için aksiyon butonlarını alır.
     *
     * @param $row
     * @param $row : Her bir satırın verisi (örneğin: bir model nesnesi).
     *
     * Kullanıcılar, her satır için butonlar tanımlayabilirler. Örnek:
     * - Güncelleme butonu
     * - Silme butonu
     *
     * Örnek geri dönüş:
     * return '<button onclick="update('.$row->id.')" class="btn btn-warning">Güncelle</button>
     *         <button onclick="delete('.$row->id.')" class="btn btn-danger">Sil</button>';
     * @return string
     *
     * Bu metod, kullanıcılar tarafından aksi takdirde implement edilmesi gereken bir metoddur.
     * Kullanıcılar bu metod içinde, her satır için özelleştirilmiş aksiyon butonlarını tanımlamalıdır.
     *
     */
    abstract protected function getActionButtons($row);

}
