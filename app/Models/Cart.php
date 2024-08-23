<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table="cart";
    protected $fillable = ['user_id', 'p_id', 'mass'];

    public function product()
    {
        return $this->belongsTo(VegeProduct::class, 'p_id');
    }
}
