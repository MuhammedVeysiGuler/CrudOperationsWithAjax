<?php

namespace App\Http\Controllers;

use App\Interfaces\BaseServiceInterface;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $service;

    public function __construct(BaseServiceInterface $service)
    {
        $this->service = $service;
    }

    public function fetchData()
    {
        return $this->service->getAll();
    }

    public function getData($id)
    {
        return $this->service->findById($id);
    }

    public function createData(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function updateData(Request $request, $id)
    {
        return $this->service->update($id, $request->all());
    }

    public function deleteData($id)
    {
        return $this->service->delete($id);
    }

}
