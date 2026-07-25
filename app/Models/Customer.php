<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
        'loyalty_points'
    ];
}
