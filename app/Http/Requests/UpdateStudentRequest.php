<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'email' => 'required|email|unique:students,email,' . $this->updateId . ',id',
            'updateId' => 'required|exists:students,id',
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
            'updateId' => 'Öğrenci ID',
        ];
    }
}
