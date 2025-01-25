<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Interfaces\Student\StudentServiceInterface;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentController extends BaseController
{
    protected $studentService;

    public function __construct(StudentServiceInterface $studentService)
    {
        parent::__construct($studentService);
        $this->studentService = $studentService;
    }


    public function index()
    {
        $cities = ['Ankara', 'Istanbul', 'Izmir'];
        $lessons = Lesson::all();
        return view('panel.student.index', compact('cities', 'lessons'));
    }


    public function getStudent(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|distinct|exists:students,id',
        ]);

        $student = parent::get($validated['id']);

        return response()->json([
            'name' => $student->name,
            'surname' => $student->surname,
            'city' => $student->city,
            'email' => $student->email,
            'updateId' => $student->id,
            'lesson_id' => $student->lesson ? $student->lesson->id : null,
        ], 200);
    }


    public function createStudent(CreateStudentRequest $request)
    {

        $student = parent::create($request);

        return response()->json([
            'message' => 'Student created successfully.',
            'student' => $student,
        ], 201);
    }


    public function updateStudent(UpdateStudentRequest $request)
    {

        $student = parent::update($request, $request->updateId);

        return response()->json([
            'message' => 'Student updated successfully.',
            'student' => $student,
        ], 200);
    }

    public function deleteStudent(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|distinct|exists:students,id',
        ]);

        parent::delete($validated['id']);

        return response()->json([
            'message' => 'Student deleted successfully.',
        ], 200);
    }
}
