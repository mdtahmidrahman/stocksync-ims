<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use \App\Traits\BelongsToCompany;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'company_id',
        'payable_id',
        'payable_type',
        'amount',
        'payment_method',
        'payment_date',
        'reference_number',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }
}
