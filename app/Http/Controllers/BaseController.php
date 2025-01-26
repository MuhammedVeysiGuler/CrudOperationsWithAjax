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


    public function fetchDataTable(Request $request)
    {
        return $this->service->getDataTable($request->all());
    }


    public function get($id)
    {
        return $this->service->findById($id);
    }


    public function create(Request $request)
    {
        return $this->service->create($request->all());
    }


    public function update(Request $request, $id)
    {
        return $this->service->update($id, $request->all());
    }


    public function delete($id)
    {
        return $this->service->delete($id);
    }

}
