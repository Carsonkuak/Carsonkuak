<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VegeAddress extends Model
{
    use HasFactory;

    protected $table = 'vege_address';

    protected $fillable = [
        'u_id',
        'address_1',
        'address_2',
        'city',
        'state',
        'postcode',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }
}
