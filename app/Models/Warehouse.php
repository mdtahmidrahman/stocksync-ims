<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $fillable = ['name', 'location', 'is_active', 'company_id'];
}
