<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'company_id',
        'supplier_id',
        'user_id',
        'reference_number',
        'status',
        'total_amount',
        'notes'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
