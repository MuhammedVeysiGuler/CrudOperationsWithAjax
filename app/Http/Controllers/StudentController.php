<?php

namespace App\Http\Controllers;

use App\Interfaces\Student\StudentServiceInterface;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentController extends BaseController
{
    protected $studentService;

    public function __construct(StudentServiceInterface $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index()
    {
        $cities = ['Ankara', 'Istanbul', 'Izmir'];
        return view('panel.student.index', compact('cities'));
    }

    public function fetch(Request $request)
    {
        return $this->studentService->getDataTable($request->all());
    }

    public function get(Request $request)
    {
        $student = $this->studentService->findById($request->id);
        return response([
            'name' => $student->name,
            'surname' => $student->surname,
            'city' => $student->city,
            'email' => $student->email,
            'updateId' => $student->id,
        ]);
    }

    // Custom create metodu
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email'
        ]);

        try {
            $student = $this->studentService->create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Öğrenci başarıyla oluşturuldu',
                'data' => $student
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Öğrenci oluşturulurken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Custom update metodu
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $request->updateId . ',id',
            'updateId' => 'required',
        ]);

        try {
            $student = $this->studentService->update($request->updateId, $validated);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Öğrenci bulunamadı'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Öğrenci başarıyla güncellendi',
                'data' => $student
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Öğrenci güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Custom delete metodu
    public function delete(Request $request)
    {
        try {
            $result = $this->studentService->delete($request->id);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Öğrenci bulunamadı'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Öğrenci başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Öğrenci silinirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
