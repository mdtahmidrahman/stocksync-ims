<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Auto-check low stock items for company
        if ($user && $user->company_id && ($user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('super_admin'))) {
            $lowStockProducts = \App\Models\Product::where('company_id', $user->company_id)
                ->where('stock_quantity', '<=', 10)
                ->take(5)
                ->get();

            foreach ($lowStockProducts as $product) {
                // Check if notification already exists recently (in last 12 hours)
                $exists = $user->notifications()
                    ->where('created_at', '>=', now()->subHours(12))
                    ->where('data->title', 'Low Stock Alert')
                    ->where('data->message', 'like', "%{$product->sku}%")
                    ->exists();

                if (!$exists) {
                    $user->notify(new \App\Notifications\SystemAlertNotification(
                        'Low Stock Alert',
                        "Product '{$product->name}' (SKU: {$product->sku}) is running low on stock ({$product->stock_quantity} remaining).",
                        'alert',
                        $product->image_path
                    ));
                }
            }
        }
        
        return response()->json([
            'notifications' => $user->notifications()->take(20)->get(),
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}
