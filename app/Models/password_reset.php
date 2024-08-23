<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class password_reset extends Model
{
    protected $table="password_reset_tokens";
    use HasFactory;

    protected $casts=[
        "token"=>"hashed",
    ];
}
