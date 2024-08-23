<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VegeProduct extends Model
{
    use HasFactory;

    protected $table = 'vege_product';  // Ensure this matches your actual table name

    protected $fillable = [
        'user_id',
        'p_id',
        'image',
        'p_name',
        'details',
        'mass',
        'price',
        'created_at',
        'updated_at',
    ];

    // Other model methods and properties
}
    