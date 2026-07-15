<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'company_id',
        'customer_id',
        'user_id',
        'warehouse_id',
        'invoice_number',
        'status',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'payment_method'
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
