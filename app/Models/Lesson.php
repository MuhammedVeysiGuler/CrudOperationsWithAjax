<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = ['name', 'akts', 'code'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
