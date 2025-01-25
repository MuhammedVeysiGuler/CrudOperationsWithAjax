<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStudentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'lesson_id' => 'required|numeric|exists:lessons,id',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Ad',
            'surname' => 'Soyad',
            'city' => 'Şehir',
            'email' => 'E-posta',
            'lesson_id' => 'Ders',
        ];
    }
}
