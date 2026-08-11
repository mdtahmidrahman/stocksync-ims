<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransfer extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'company_id',
        'source_warehouse_id',
        'destination_warehouse_id',
        'product_id',
        'user_id',
        'quantity',
        'status',
        'notes',
    ];

    public function sourceWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
