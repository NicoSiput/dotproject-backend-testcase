<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //

    protected $primaryKey = "productid";
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'weight', 'description'
    ];


    protected $hidden = [];

    public function productPrice()
    {
        return $this->hasMany(ProductPrice::class, 'productid');
    }

    public function productPicture()
    {
        return $this->hasMany(productPicture::class, 'productid');
    }
}
