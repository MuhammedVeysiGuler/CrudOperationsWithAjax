<?php

namespace App\Http\Controllers;

use App\Models\SignIn;
use Illuminate\Http\Request;
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
                return $data->name ." ". $data->surname;
            })
            ->addColumn('delete', function ($data) {
                return "<button onclick='deleteSingIn(" . $data->id . ")' class='btn btn-danger'>Sil</button>";
            })
            ->rawColumns(['name','delete'])
            ->make(true);
    }

    public function create(Request $request){
        $request->validate([
           'name' => 'required',
            'surname' => 'required',
            'city' => 'required',
            'mail' => 'required | email'
        ]);
        $sign_in = new SignIn();
        $sign_in->name = $request->name;
        $sign_in->surname = $request->surname;
        $sign_in->city = $request->city;
        $sign_in->email = $request->mail;
        $sign_in->save();
        return response()->json(['Success' => 'success']);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'distinct'
        ]);
        SignIn::find($request->id)->delete();
        return response()->json(['Success' => 'success']);
    }
}
