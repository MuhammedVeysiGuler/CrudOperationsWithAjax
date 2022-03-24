<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SignIn extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sign_in';
    protected $guarded=['id'];

}
