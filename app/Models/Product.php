<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'company_id', 'category_id', 'name', 'sku', 'description',
        'price', 'cost', 'stock_quantity', 'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
