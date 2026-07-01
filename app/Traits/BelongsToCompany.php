<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    /**
     * Boot the trait to automatically apply the TenantScope and set the company_id on creation.
     */
    protected static function bootBelongsToCompany(): void
    {
        // Apply the global scope to filter queries by the current user's company
        static::addGlobalScope(new TenantScope);

        // Automatically assign the company_id when creating a new record
        static::creating(function ($model) {
            if (Auth::check() && !Auth::user()->isSuperAdmin()) {
                $model->company_id = Auth::user()->company_id;
            }
        });
    }

    /**
     * Define the relationship to the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
