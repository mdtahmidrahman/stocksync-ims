<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use \App\Traits\BelongsToCompany;
    use \App\Traits\LogsActivity;

    protected $fillable = ['name', 'location', 'is_active', 'company_id', 'manager_id', 'capacity'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
