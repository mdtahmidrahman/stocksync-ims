<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $company = Company::find(Auth::user()->company_id);

        return Inertia::render('Settings', [
            'company' => $company
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'currency' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:100',
            'pos_tax_rate' => 'nullable|numeric|min:0|max:100',
            'pos_receipt_footer' => 'nullable|string',
            'printer_type' => 'nullable|string|max:100',
            'auto_print_receipt' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $company = Company::findOrFail(Auth::user()->company_id);
        
        $data = $request->only([
            'name',
            'phone',
            'email',
            'address',
            'currency',
            'timezone',
            'pos_tax_rate',
            'pos_receipt_footer',
            'printer_type',
            'auto_print_receipt',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company_logos', 'public');
            $data['logo_path'] = $path;
        }

        $company->update(array_filter($data, fn($v) => !is_null($v)));

        AuditLog::record('System Settings Updated', "Updated company settings for '{$company->name}'.");

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    public function backup()
    {
        $companyId = Auth::user()->company_id;
        $company = Company::find($companyId);
        $filename = 'backup_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $company->name ?? 'company')) . '_' . date('Y_m_d_His') . '.sql';

        $tables = ['products', 'categories', 'warehouses', 'suppliers', 'customers', 'sales', 'sale_items', 'orders', 'order_items', 'purchases', 'purchase_items', 'payments', 'warehouse_transfers', 'audit_logs'];
        
        $sql = "-- StockSync IMS Database Backup\n";
        $sql .= "-- Company: " . ($company->name ?? 'N/A') . " (ID: {$companyId})\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            try {
                $query = DB::table($table);
                if (\Schema::hasColumn($table, 'company_id')) {
                    $query->where('company_id', $companyId);
                }
                $rows = $query->get();

                if ($rows->count() > 0) {
                    $sql .= "-- Table: {$table}\n";
                    foreach ($rows as $row) {
                        $array = (array) $row;
                        $keys = array_keys($array);
                        $values = array_map(function ($val) {
                            if (is_null($val)) return 'NULL';
                            return "'" . addslashes($val) . "'";
                        }, array_values($array));

                        $sql .= "INSERT INTO `{$table}` (`" . implode('`, `', $keys) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            } catch (\Exception $e) {
                // Ignore missing tables
            }
        }

        AuditLog::record('Database Backup Generated', "Downloaded SQL database backup dump.");

        return response($sql, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt|max:10240',
        ]);

        try {
            $content = file_get_contents($request->file('sql_file')->getRealPath());
            $statements = array_filter(array_map('trim', explode(';', $content)));

            foreach ($statements as $statement) {
                if (!empty($statement) && !str_starts_with($statement, '--')) {
                    DB::statement($statement);
                }
            }

            AuditLog::record('System Restored', "Restored database state from uploaded SQL file.");

            return redirect()->back()->with('success', 'Database restored successfully from backup.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
}

