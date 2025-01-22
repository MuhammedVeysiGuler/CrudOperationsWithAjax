<?php

namespace App\Http\Controllers;

use App\Interfaces\SignInServiceInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SignInController extends BaseController
{
    protected $signInService;

    public function __construct(SignInServiceInterface $service)
    {
        parent::__construct($service);
        $this->signInService = $service;
    }

    public function index()
    {
        return view('panel.login.index');
    }

    public function fetch(Request $request)
    {
        return $this->signInService->getDataTable();
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'city' => 'required',
            'mail' => 'required|email'
        ]);

        $data = [
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'city' => $validated['city'],
            'email' => $validated['mail']
        ];

        $this->signInService->create($data);
        return response()->json(['Success' => 'success']);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sign_ins,id'
        ]);

        try {
            $this->signInService->delete($request->id);
            return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting record'], 500);
        }
    }

    public function get(Request $request)
    {
        $signIn = $this->signInService->findById($request->id);
        return response([
            'name' => $signIn->name,
            'surname' => $signIn->surname,
            'city' => $signIn->city,
            'mail' => $signIn->email,
        ]);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'city' => 'required',
            'mail' => 'required|email',
            'updateId' => 'distinct',
        ]);

        return parent::update($request, $request->updateId);
    }

    public function update_view($id){
        $signIn = $this->signInService->findById($id);
        return view('panel.login.update', compact('signIn','id'));
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
