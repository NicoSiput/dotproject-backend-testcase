<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPicture extends Model
{
    protected $primaryKey = "product_pictureid";

    protected $fillable = [
        'productid', 'filepath'
    ];

    protected $hidden = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productid');
    }

    public function getFilepathAttribute($value)
    {
        return url('storage/' . $value);
    }
}
