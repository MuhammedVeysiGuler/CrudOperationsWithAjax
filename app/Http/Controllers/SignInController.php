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
        $veri1 = \request()->veri1;
                                        //datatable tarafından gönderilen harici veriler
        $veri2 = \request()->veri2;

        $signIn = SignIn::all();
        return DataTables::of($signIn)
            ->editColumn('name', function ($data) {
                return $data->name . " " . $data->surname;
            })
            ->addColumn('delete', function ($data) {
                return "<button onclick='deleteSignIn(" . $data->id . ")' class='btn btn-danger'>Sil</button>";
            })
            ->addColumn('update', function ($data) {
                return "<button onclick='updateSignIn(" . $data->id . ")' class='btn btn-warning'>Güncelle</button>";
            })
            ->rawColumns(['name', 'delete', 'update'])
            ->make(true);
    }

    public function create(Request $request)
    {
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

    public function get(Request $request)
    {

        $signIn = SignIn::where('id', $request->id)->first();
        return response([
            'name' => $signIn->name,
            'surname' => $signIn->surname,
            'city' => $signIn->city,
            'mail' => $signIn->email,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'city' => 'required',
            'mail' => 'required | email',
            'updateId' => 'distinct',
        ]);
        SignIn::where('id', $request->updateId)->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'city' => $request->city,
            'email' => $request->mail,
        ]);
        return response()->json(['Success' => 'success']);
    }

    public function pdf(Request $request)
    {
        if (!empty($_POST['data'])) {
            $base64Data = explode("application/pdf;base64,", $request->data);
            $data = base64_decode($base64Data[1]);  //base64 olan kısım alınır
            $fileName = $_POST['filename'];

            file_put_contents("uploads/" . $fileName, $data);   // ==> "uploads" path dosyası public altında oluşturulmak zorunda, oto oluşturmuyor.
            return response()->json(['Success' => 'success']);
        } else {
            return response()->json(['Error' => 'error']);
        }
    }

    //başka bir base64 to pdf fonksiyonu
    /* public function uploadFileFromBlobString($base64string = '', $file_name = 'test', $folder = 'uploads',)
     {

         $file_path = "";
         $result = 0;

         // Convert blob (base64 string) back to PDF
         if (!empty($base64string)) {

             // Detects if there is base64 encoding header in the string.
             // If so, it needs to be removed prior to saving the content to a phisical file.
             if (strpos($base64string, ',') !== false) {
                 @list($encode, $base64string) = explode(',', $base64string);
             }

             $base64data = base64_decode($base64string, true);
             $file_path  = "{$folder}/{$file_name}";

             // Return the number of bytes saved, or false on failure
             $result = file_put_contents("{$this->_assets_path}/{$file_path}", $base64data);
         }

         return $result;
     }
    */

}
