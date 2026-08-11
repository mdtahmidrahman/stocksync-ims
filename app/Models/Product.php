<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \App\Traits\BelongsToCompany;
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'company_id', 'category_id', 'name', 'sku', 'description',
        'price', 'cost', 'stock_quantity', 'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    protected static function booted()
    {
        static::updated(function ($product) {
            // Check if stock dropped to 10 or below, but was previously above 10
            if ($product->isDirty('stock_quantity') && $product->stock_quantity <= 10 && $product->getOriginal('stock_quantity') > 10) {
                // Find all admins and managers in this company
                $users = \App\Models\User::role(['admin', 'manager'])
                                         ->where('company_id', $product->company_id)
                                         ->get();
                
                if ($users->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\SystemAlertNotification(
                        'Low Stock Alert', 
                        "Product '{$product->name}' (SKU: {$product->sku}) is running low on stock ({$product->stock_quantity} remaining).", 
                        'alert',
                        $product->image_path
                    ));
                }
            }
        });
    }
}
