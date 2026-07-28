<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use \App\Traits\BelongsToCompany;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address'
    ];
}
