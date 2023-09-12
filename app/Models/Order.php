<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'date', 'product_id', 'quantity', 'total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Puedes agregar relaciones adicionales aquí si es necesario
}
