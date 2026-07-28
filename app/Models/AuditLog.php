<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class AuditLog extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'user_id',
        'event',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($event, $description)
    {
        return static::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'description' => $description,
            'ip_address' => request() ? request()->ip() : null,
        ]);
    }
}
