<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    use HasFactory;

    //getter fo shipping_cost
    public function getShippingCostAttribute($value)
    {
        return (int) $value;
    } 
}
