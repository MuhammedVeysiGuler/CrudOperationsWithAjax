<?php

namespace App\Http\Controllers;

use App\Models\SignIn;
use Yajra\DataTables\DataTables;

class SignInController extends Controller
{
    public function index()
    {
        return view('panel.login.index');
    }

    public function fetch()
    {
        $signIn = SignIn::all();
        return DataTables::of($signIn)
           ->editColumn('name', function ($data) {
                return $data->name ." - ". $data->surname;
            })
            ->rawColumns(['name'])
            ->make(true);
    }
}
