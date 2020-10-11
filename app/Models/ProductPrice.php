<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    //
    protected $primaryKey = "product_priceid";

    protected $fillable = [
        'productid', 'min_qty', 'max_qty', 'price'
    ];

    protected $hidden = [];
}
