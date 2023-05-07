<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
        use HasFactory;

        const UPDATE_AT = null;
        protected $fillable = [
        'email', 'token'
    ];
}
