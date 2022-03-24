<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignIn extends Model
{
    use HasFactory;
    protected $table = 'sign_in';
    protected $guarded=['id'];

}
