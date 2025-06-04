<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'license',
        'car_id',
        'quantity',
        'price_per_day',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'days'
    ];
}
