<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Use Auth::hasUser() to prevent infinite loop when Laravel resolves the Auth user.
        if (Auth::hasUser() && !Auth::user()->isSuperAdmin()) {
            $builder->where($model->getTable() . '.company_id', Auth::user()->company_id);
        }
    }
}
