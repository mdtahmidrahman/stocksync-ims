<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\WarehouseTransfer;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        // Set currency to Bangladeshi Taka
        $company->currency = '৳';
        $company->save();

        $admin = User::where('role', 'admin')->first() ?? User::first();
        $manager = User::where('role', 'manager')->first() ?? User::first();
        $staff = User::where('role', 'staff')->first() ?? User::first();
        $allUsers = User::whereNotNull('company_id')->get();

        // -------------------------------------------------------------
        // 1. Categories
        // -------------------------------------------------------------
        $categoryData = [
            'Smartphones & Mobiles',
            'Laptops & Computers',
            'Computer Accessories',
            'Networking & WiFi',
            'Audio & Wearables',
            'Office Electronics',
            'TV & Home Appliances',
            'PC Components'
        ];

        $categories = [];
        foreach ($categoryData as $catName) {
            $categories[$catName] = Category::create([
                'company_id' => $company->id,
                'name' => $catName,
                'is_active' => true
            ]);
        }

        // -------------------------------------------------------------
        // 2. Suppliers (Real BD Tech Distributors)
        // -------------------------------------------------------------
        $supplierData = [
            ['name' => 'Smart Technologies (BD) Ltd', 'email' => 'corporate@smart-bd.com', 'phone' => '+8801711001122', 'address' => 'Smart Tower, Agargaon, Dhaka'],
            ['name' => 'Star Tech & Engineering Ltd', 'email' => 'info@startech.com.bd', 'phone' => '+8801977223344', 'address' => 'IDB Bhaban, Agargaon, Dhaka'],
            ['name' => 'Flora Limited', 'email' => 'sales@floralimited.com', 'phone' => '+8801819556677', 'address' => 'Flora Center, Motijheel C/A, Dhaka'],
            ['name' => 'Ryans Computers Ltd', 'email' => 'corporate@ryanscomputers.com', 'phone' => '+8801755667788', 'address' => 'Kusholi Bhaban, Elephant Road, Dhaka'],
            ['name' => 'Techland Bangladesh', 'email' => 'support@techlandbd.com', 'phone' => '+8801911998877', 'address' => 'Multiplan Center, New Elephant Road, Dhaka'],
            ['name' => 'Global Brand Private Ltd', 'email' => 'sales@globalbrand.com.bd', 'phone' => '+8801730001122', 'address' => '70 Bir Uttam C.R. Datta Road, Dhanmondi, Dhaka'],
            ['name' => 'Computer City Technologies Ltd', 'email' => 'info@cctl.com.bd', 'phone' => '+8801841112233', 'address' => 'Stadium Market, Agrabad, Chittagong'],
            ['name' => 'UCC Bangladesh', 'email' => 'corporate@ucc-bd.com', 'phone' => '+8801922334455', 'address' => 'Computer City Center, Dhaka']
        ];

        $suppliers = [];
        foreach ($supplierData as $sup) {
            $suppliers[] = Supplier::create([
                'company_id' => $company->id,
                'name' => $sup['name'],
                'email' => $sup['email'],
                'phone' => $sup['phone'],
                'address' => $sup['address']
            ]);
        }

        // -------------------------------------------------------------
        // 3. Customers (Corporate Companies for Orders & Individuals for Sales)
        // -------------------------------------------------------------
        $corporateCustomers = [
            ['name' => 'Apex Footwear Ltd (IT Div)', 'email' => 'it.procurement@apexfootwear.com', 'phone' => '+8801711900111', 'address' => 'Gulshan 1, Dhaka'],
            ['name' => 'Akij Group (Corporate)', 'email' => 'tech@akij.net', 'phone' => '+8801819123456', 'address' => 'Akij House, Tejgaon, Dhaka'],
            ['name' => 'Walton Digi-Tech Ltd', 'email' => 'corporate@waltonbd.com', 'phone' => '+8801977001122', 'address' => 'Bashundhara R/A, Dhaka'],
            ['name' => 'Square Pharmaceuticals Ltd', 'email' => 'info@squarepharma.com.bd', 'phone' => '+8801730112233', 'address' => 'Square Centre, Mohakhali, Dhaka'],
            ['name' => 'Beximco Computers Division', 'email' => 'procurement@beximco.net', 'phone' => '+8801841223344', 'address' => 'Dhanmondi R/A, Dhaka'],
            ['name' => 'Grameenphone Corporate Sales', 'email' => 'b2b@grameenphone.com', 'phone' => '+8801711556677', 'address' => 'GPHouse, Bashundhara, Dhaka'],
            ['name' => 'Robi Axiata Enterprise Solutions', 'email' => 'enterprise@robi.com.bd', 'phone' => '+8801819009988', 'address' => 'Nitol Niloy Tower, Nikunja 2, Dhaka'],
            ['name' => 'PRAN-RFL Group IT Division', 'email' => 'it@prangroup.com', 'phone' => '+8801911223344', 'address' => 'PRAN-RFL Centre, Middle Badda, Dhaka']
        ];

        $individualCustomers = [
            ['name' => 'Tanvir Hossain', 'email' => 'tanvir.hossain@gmail.com', 'phone' => '+8801712345678', 'address' => 'Mirpur 10, Dhaka'],
            ['name' => 'Anika Rahman', 'email' => 'anika.rahman@yahoo.com', 'phone' => '+8801812345679', 'address' => 'Dhanmondi 27, Dhaka'],
            ['name' => 'Md. Saiful Islam', 'email' => 'saiful.islam@outlook.com', 'phone' => '+8801912345680', 'address' => 'Agrabad, Chittagong'],
            ['name' => 'Karim Uddin', 'email' => 'uddin.karim@gmail.com', 'phone' => '+8801612345681', 'address' => 'Zindabazar, Sylhet'],
            ['name' => 'Arif Chowdhury', 'email' => 'arif.chowdhury@hotmail.com', 'phone' => '+8801755443322', 'address' => 'Uttara Sector 4, Dhaka'],
            ['name' => 'Sulaiman Ahmed', 'email' => 's.ahmed@gmail.com', 'phone' => '+8801855443322', 'address' => 'Sylhet Sadar, Sylhet'],
            ['name' => 'Faria Ahmed', 'email' => 'faria.ahmed@yahoo.com', 'phone' => '+8801955443322', 'address' => 'Banani, Dhaka'],
            ['name' => 'Mahmudul Hasan', 'email' => 'mahmud.h@gmail.com', 'phone' => '+8801655443322', 'address' => 'Saheb Bazar, Rajshahi']
        ];

        $corpCustomerModels = [];
        foreach ($corporateCustomers as $c) {
            $corpCustomerModels[] = Customer::create([
                'company_id' => $company->id,
                'name' => $c['name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $c['address'],
                'loyalty_points' => rand(500, 3000)
            ]);
        }

        $indCustomerModels = [];
        foreach ($individualCustomers as $c) {
            $indCustomerModels[] = Customer::create([
                'company_id' => $company->id,
                'name' => $c['name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $c['address'],
                'loyalty_points' => rand(50, 500)
            ]);
        }

        $allCustomers = array_merge($corpCustomerModels, $indCustomerModels);

        // -------------------------------------------------------------
        // 4. Warehouses (Real BD Locations)
        // -------------------------------------------------------------
        $warehouseData = [
            ['name' => 'Dhaka Central Warehouse', 'location' => 'Motijheel C/A, Dhaka', 'manager_id' => $manager->id, 'capacity' => 50000],
            ['name' => 'Uttara Distribution Hub', 'location' => 'Sector 7, Uttara, Dhaka', 'manager_id' => $admin->id, 'capacity' => 35000],
            ['name' => 'Chittagong Port Depot', 'location' => 'Agrabad C/A, Chittagong', 'manager_id' => $staff->id, 'capacity' => 40000],
            ['name' => 'Sylhet Regional Hub', 'location' => 'Zindabazar, Sylhet', 'manager_id' => $manager->id, 'capacity' => 25000],
            ['name' => 'Rajshahi Branch Depot', 'location' => 'Saheb Bazar, Rajshahi', 'manager_id' => $staff->id, 'capacity' => 20000]
        ];

        $warehouses = [];
        foreach ($warehouseData as $w) {
            $warehouses[] = Warehouse::create([
                'company_id' => $company->id,
                'name' => $w['name'],
                'location' => $w['location'],
                'manager_id' => $w['manager_id'],
                'capacity' => $w['capacity'],
                'is_active' => true
            ]);
        }

        // -------------------------------------------------------------
        // 5. Products (Authentic Tech Products with Realistic BDT Prices)
        // -------------------------------------------------------------
        $productCatalog = [
            // Smartphones
            ['cat' => 'Smartphones & Mobiles', 'name' => 'Apple iPhone 15 Pro Max (256GB)', 'sku' => 'APP-IP15PM-256', 'cost' => 145000, 'price' => 165000, 'img' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=500'],
            ['cat' => 'Smartphones & Mobiles', 'name' => 'Samsung Galaxy S24 Ultra (512GB)', 'sku' => 'SAM-S24U-512', 'cost' => 138000, 'price' => 158000, 'img' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=500'],
            ['cat' => 'Smartphones & Mobiles', 'name' => 'Xiaomi Redmi Note 13 Pro+ 5G', 'sku' => 'XIA-RN13P-256', 'cost' => 38000, 'price' => 44500, 'img' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500'],
            ['cat' => 'Smartphones & Mobiles', 'name' => 'Google Pixel 8 Pro (128GB)', 'sku' => 'GGL-PX8P-128', 'cost' => 88000, 'price' => 102000, 'img' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500'],

            // Laptops & Computers
            ['cat' => 'Laptops & Computers', 'name' => 'Asus ROG Strix G16 Gaming Laptop', 'sku' => 'ASU-ROG-G16', 'cost' => 185000, 'price' => 215000, 'img' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500'],
            ['cat' => 'Laptops & Computers', 'name' => 'HP Pavilion 15 (Core i5 13th Gen)', 'sku' => 'HP-PAV15-I5', 'cost' => 68000, 'price' => 78500, 'img' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500'],
            ['cat' => 'Laptops & Computers', 'name' => 'Dell XPS 13 Touch (Intel i7 16GB)', 'sku' => 'DEL-XPS13-I7', 'cost' => 148000, 'price' => 172000, 'img' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500'],
            ['cat' => 'Laptops & Computers', 'name' => 'Apple MacBook Air M2 (8GB / 256GB)', 'sku' => 'APP-MBA-M2', 'cost' => 112000, 'price' => 128000, 'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500'],
            ['cat' => 'Laptops & Computers', 'name' => 'Lenovo IdeaPad Slim 3 (Ryzen 5)', 'sku' => 'LEN-IPS3-R5', 'cost' => 52000, 'price' => 59500, 'img' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=500'],

            // Accessories
            ['cat' => 'Computer Accessories', 'name' => 'Logitech MX Master 3S Wireless Mouse', 'sku' => 'LOG-MX3S-BLK', 'cost' => 9500, 'price' => 11500, 'img' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=500'],
            ['cat' => 'Computer Accessories', 'name' => 'Keychron K2 V2 Wireless Keyboard', 'sku' => 'KEY-K2V2-RGB', 'cost' => 7800, 'price' => 9500, 'img' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500'],
            ['cat' => 'Computer Accessories', 'name' => 'Baseus 100W GaN Fast Charger', 'sku' => 'BAS-100W-GAN', 'cost' => 2800, 'price' => 3600, 'img' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500'],
            ['cat' => 'Computer Accessories', 'name' => 'UGREEN USB-C Multiport 7-in-1 Hub', 'sku' => 'UGR-USBC-7IN1', 'cost' => 3200, 'price' => 4200, 'img' => 'https://images.unsplash.com/photo-1625842268584-8f3296236761?w=500'],
            ['cat' => 'Computer Accessories', 'name' => 'Anker PowerCore 20000mAh Power Bank', 'sku' => 'ANK-PC20K-BLK', 'cost' => 3200, 'price' => 4200, 'img' => 'https://images.unsplash.com/photo-1609592424009-5984620857aa?w=500'],
            ['cat' => 'Computer Accessories', 'name' => 'Redragon M601 RGB Gaming Mouse', 'sku' => 'RED-M601-RGB', 'cost' => 1350, 'price' => 1750, 'img' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500'],

            // Networking
            ['cat' => 'Networking & WiFi', 'name' => 'TP-Link Archer AX72 WiFi 6 Router', 'sku' => 'TPL-AX72-WIFI6', 'cost' => 8200, 'price' => 10500, 'img' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=500'],
            ['cat' => 'Networking & WiFi', 'name' => 'Cisco Catalyst 24-Port Switch', 'sku' => 'CSC-CAT24-GB', 'cost' => 34000, 'price' => 41000, 'img' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=500'],
            ['cat' => 'Networking & WiFi', 'name' => 'D-Link 8-Port Gigabit Desktop Switch', 'sku' => 'DLK-8P-GB', 'cost' => 1800, 'price' => 2400, 'img' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=500'],

            // Audio & Wearables
            ['cat' => 'Audio & Wearables', 'name' => 'Sony WH-1000XM5 Wireless Headphones', 'sku' => 'SNY-WH1000XM5', 'cost' => 33000, 'price' => 39500, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500'],
            ['cat' => 'Audio & Wearables', 'name' => 'Apple AirPods Pro 2nd Gen (Type-C)', 'sku' => 'APP-AIRPODS-P2', 'cost' => 22500, 'price' => 26800, 'img' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=500'],
            ['cat' => 'Audio & Wearables', 'name' => 'Xiaomi Smart Band 8 (Black)', 'sku' => 'XIA-BAND8-BLK', 'cost' => 3500, 'price' => 4500, 'img' => 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?w=500'],
            ['cat' => 'Audio & Wearables', 'name' => 'JBL Flip 6 Portable Bluetooth Speaker', 'sku' => 'JBL-FLIP6-BLK', 'cost' => 10500, 'price' => 12800, 'img' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500'],
            ['cat' => 'Audio & Wearables', 'name' => 'Fantech Captain 7.1 Gaming Headset', 'sku' => 'FAN-HG11-71', 'cost' => 2100, 'price' => 2750, 'img' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=500'],

            // Office Electronics
            ['cat' => 'Office Electronics', 'name' => 'Epson EcoTank L3210 Printer', 'sku' => 'EPS-L3210-INK', 'cost' => 17500, 'price' => 21500, 'img' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=500'],
            ['cat' => 'Office Electronics', 'name' => 'Hikvision 4MP Outdoor IP Camera', 'sku' => 'HIK-4MP-IPCAM', 'cost' => 4800, 'price' => 6200, 'img' => 'https://images.unsplash.com/photo-1557862921-37829c790f19?w=500'],

            // TV & Home Appliances
            ['cat' => 'TV & Home Appliances', 'name' => 'Walton 43-inch 4K Smart Android TV', 'sku' => 'WLT-43K-SMART', 'cost' => 31000, 'price' => 37500, 'img' => 'https://images.unsplash.com/photo-1593784991095-a205069470b6?w=500'],

            // PC Components
            ['cat' => 'PC Components', 'name' => 'Samsung 27-inch Odyssey G5 165Hz Monitor', 'sku' => 'SAM-27G5-165', 'cost' => 24500, 'price' => 29500, 'img' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500'],
            ['cat' => 'PC Components', 'name' => 'Samsung 980 PRO 1TB PCIe M.2 SSD', 'sku' => 'SAM-980PRO-1TB', 'cost' => 10800, 'price' => 13200, 'img' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500'],
            ['cat' => 'PC Components', 'name' => 'Corsair Vengeance 16GB DDR5 RAM', 'sku' => 'CRS-16GB-DDR5', 'cost' => 6200, 'price' => 7800, 'img' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=500'],
            ['cat' => 'PC Components', 'name' => 'MSI GeForce RTX 4060 Ventus 8GB GPU', 'sku' => 'MSI-RTX4060-8G', 'cost' => 36500, 'price' => 43500, 'img' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=500']
        ];

        $products = [];
        foreach ($productCatalog as $item) {
            $catModel = $categories[$item['cat']] ?? reset($categories);
            
            $product = Product::create([
                'company_id' => $company->id,
                'category_id' => $catModel->id,
                'name' => $item['name'],
                'sku' => $item['sku'],
                'description' => 'Official Bangladesh warranty item. Top seller.',
                'price' => $item['price'],
                'cost' => $item['cost'],
                'stock_quantity' => 0,
                'image_path' => $item['img']
            ]);

            $products[] = $product;

            // Distribute stock across warehouses
            $totalStock = 0;
            foreach ($warehouses as $wh) {
                $qty = rand(20, 250);
                $totalStock += $qty;
                $wh->products()->attach($product->id, ['quantity' => $qty]);
            }

            $product->update(['stock_quantity' => $totalStock]);
        }

        // -------------------------------------------------------------
        // 6. Purchases (Restocking from BD Suppliers)
        // -------------------------------------------------------------
        $purchases = [];
        for ($i = 1; $i <= 18; $i++) {
            $purchaseDate = Carbon::now()->subDays(rand(2, 120));
            $sup = $suppliers[array_rand($suppliers)];
            $wh = $warehouses[array_rand($warehouses)];
            
            $purchase = Purchase::create([
                'company_id' => $company->id,
                'supplier_id' => $sup->id,
                'warehouse_id' => $wh->id,
                'purchase_number' => 'PUR-' . $purchaseDate->format('Ym') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => ($i % 5 === 0) ? 'pending' : (($i % 9 === 0) ? 'cancelled' : 'received'),
                'total_amount' => 0,
                'created_at' => $purchaseDate,
                'updated_at' => $purchaseDate
            ]);

            $pItems = array_rand($products, rand(2, 4));
            if (!is_array($pItems)) $pItems = [$pItems];
            
            $total = 0;
            foreach ($pItems as $pIdx) {
                $p = $products[$pIdx];
                $qty = rand(10, 50);
                $subtotal = $qty * $p->cost;
                $total += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $p->id,
                    'quantity' => $qty,
                    'unit_cost' => $p->cost,
                    'subtotal' => $subtotal,
                    'created_at' => $purchaseDate,
                    'updated_at' => $purchaseDate
                ]);
            }

            $purchase->update(['total_amount' => $total]);
            $purchases[] = $purchase;
        }

        // -------------------------------------------------------------
        // 7. Sales (Retail / POS Walk-in Customers)
        // -------------------------------------------------------------
        $sales = [];
        for ($i = 1; $i <= 25; $i++) {
            $saleDate = Carbon::now()->subDays(rand(0, 90));
            $cust = $indCustomerModels[array_rand($indCustomerModels)];
            $wh = $warehouses[array_rand($warehouses)];
            $seller = $allUsers->random();

            $sale = Sale::create([
                'company_id' => $company->id,
                'customer_id' => $cust->id,
                'user_id' => $seller->id,
                'warehouse_id' => $wh->id,
                'invoice_number' => 'INV-' . $saleDate->format('Ym') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => ($i % 12 === 0) ? 'refunded' : 'completed',
                'total_amount' => 0,
                'tax_amount' => 0,
                'discount_amount' => rand(0, 500),
                'payment_method' => ['cash', 'card', 'bank_transfer'][$i % 3],
                'created_at' => $saleDate,
                'updated_at' => $saleDate
            ]);

            $sItems = array_rand($products, rand(1, 3));
            if (!is_array($sItems)) $sItems = [$sItems];

            $total = 0;
            foreach ($sItems as $pIdx) {
                $p = $products[$pIdx];
                $qty = rand(1, 4);
                $subtotal = $qty * $p->price;
                $total += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $p->id,
                    'quantity' => $qty,
                    'unit_price' => $p->price,
                    'subtotal' => $subtotal,
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate
                ]);
            }

            $sale->update(['total_amount' => max(0, $total - $sale->discount_amount)]);
            $sales[] = $sale;
        }

        // -------------------------------------------------------------
        // 8. Orders (Corporate / B2B Company Orders for `/orders`)
        // -------------------------------------------------------------
        $orders = [];
        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['unpaid', 'partial', 'paid'];

        for ($i = 1; $i <= 20; $i++) {
            $orderDate = Carbon::now()->subDays(rand(1, 60));
            $corpCust = $corpCustomerModels[array_rand($corpCustomerModels)];
            $wh = $warehouses[array_rand($warehouses)];
            $st = $orderStatuses[$i % count($orderStatuses)];
            $paySt = ($st === 'delivered') ? 'paid' : (($st === 'pending') ? 'unpaid' : $paymentStatuses[$i % count($paymentStatuses)]);

            $order = Order::create([
                'company_id' => $company->id,
                'customer_id' => $corpCust->id,
                'warehouse_id' => $wh->id,
                'order_number' => 'ORD-' . $orderDate->format('Ym') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'total_amount' => 0,
                'status' => $st,
                'payment_status' => $paySt,
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);

            $oItems = array_rand($products, rand(2, 5));
            if (!is_array($oItems)) $oItems = [$oItems];

            $total = 0;
            foreach ($oItems as $pIdx) {
                $p = $products[$pIdx];
                $qty = rand(5, 30);
                $subtotal = $qty * $p->price;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'quantity' => $qty,
                    'unit_price' => $p->price,
                    'subtotal' => $subtotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
            }

            $order->update(['total_amount' => $total]);
            $orders[] = $order;
        }

        // -------------------------------------------------------------
        // 9. Payments (Polymorphic payments for `/payments`)
        // -------------------------------------------------------------
        $paymentMethods = ['bKash', 'Nagad', 'cash', 'card', 'bank_transfer'];

        // Payments for Sales
        foreach ($sales as $sale) {
            if ($sale->status === 'completed') {
                Payment::create([
                    'company_id' => $company->id,
                    'payable_id' => $sale->id,
                    'payable_type' => Sale::class,
                    'amount' => $sale->total_amount,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_date' => $sale->created_at->format('Y-m-d'),
                    'reference_number' => 'PAY-SALE-' . Str::upper(Str::random(6)),
                    'created_at' => $sale->created_at,
                    'updated_at' => $sale->created_at
                ]);
            }
        }

        // Payments for Corporate Orders
        foreach ($orders as $order) {
            if ($order->payment_status !== 'unpaid') {
                $payAmount = ($order->payment_status === 'paid') ? $order->total_amount : ($order->total_amount / 2);
                Payment::create([
                    'company_id' => $company->id,
                    'payable_id' => $order->id,
                    'payable_type' => Order::class,
                    'amount' => $payAmount,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_date' => $order->created_at->format('Y-m-d'),
                    'reference_number' => 'PAY-ORD-' . Str::upper(Str::random(6)),
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at
                ]);
            }
        }

        // Payments for Purchases
        foreach ($purchases as $purchase) {
            if ($purchase->status === 'received') {
                Payment::create([
                    'company_id' => $company->id,
                    'payable_id' => $purchase->id,
                    'payable_type' => Purchase::class,
                    'amount' => $purchase->total_amount,
                    'payment_method' => 'bank_transfer',
                    'payment_date' => $purchase->created_at->format('Y-m-d'),
                    'reference_number' => 'PAY-PUR-' . Str::upper(Str::random(6)),
                    'created_at' => $purchase->created_at,
                    'updated_at' => $purchase->created_at
                ]);
            }
        }

        // -------------------------------------------------------------
        // 10. Warehouse Transfers (for `/warehouses` Kanban Board)
        // -------------------------------------------------------------
        $transferStatuses = ['requested', 'in_transit', 'received'];
        for ($i = 1; $i <= 15; $i++) {
            $src = $warehouses[array_rand($warehouses)];
            $dst = $warehouses[array_rand($warehouses)];
            while ($dst->id === $src->id) {
                $dst = $warehouses[array_rand($warehouses)];
            }

            $st = $transferStatuses[$i % count($transferStatuses)];
            $p = $products[array_rand($products)];
            $trDate = Carbon::now()->subDays(rand(1, 30));

            WarehouseTransfer::create([
                'company_id' => $company->id,
                'source_warehouse_id' => $src->id,
                'destination_warehouse_id' => $dst->id,
                'product_id' => $p->id,
                'user_id' => $allUsers->random()->id,
                'quantity' => rand(10, 100),
                'status' => $st,
                'notes' => 'Stock rebalancing between BD warehouses',
                'created_at' => $trDate,
                'updated_at' => $trDate
            ]);
        }

        // -------------------------------------------------------------
        // 11. Audit Logs (System Activity History for `/audit-logs`)
        // -------------------------------------------------------------
        $ips = ['127.0.0.1', '103.114.98.22', '103.205.71.10', '180.211.230.5'];

        // User Login Logs
        foreach ($allUsers as $u) {
            AuditLog::create([
                'company_id' => $company->id,
                'user_id' => $u->id,
                'event' => 'User Login',
                'description' => "User {$u->name} logged into the system.",
                'ip_address' => $ips[array_rand($ips)],
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // Product Creation Audit Logs
        foreach ($products as $p) {
            AuditLog::create([
                'company_id' => $company->id,
                'user_id' => $admin->id,
                'event' => 'Product Created',
                'description' => "Created product '{$p->name}' (SKU: {$p->sku}) with price ৳" . number_format($p->price, 2),
                'ip_address' => '127.0.0.1',
                'created_at' => $p->created_at,
            ]);
        }

        // Order Audit Logs
        foreach ($orders as $o) {
            AuditLog::create([
                'company_id' => $company->id,
                'user_id' => $allUsers->random()->id,
                'event' => 'Order Created',
                'description' => "Created Order #{$o->order_number} for customer '{$o->customer->name}' totaling ৳" . number_format($o->total_amount, 2),
                'ip_address' => $ips[array_rand($ips)],
                'created_at' => $o->created_at,
            ]);

            if ($o->status === 'delivered') {
                AuditLog::create([
                    'company_id' => $company->id,
                    'user_id' => $allUsers->random()->id,
                    'event' => 'Order Delivered',
                    'description' => "Order #{$o->order_number} was marked as delivered and stock updated.",
                    'ip_address' => $ips[array_rand($ips)],
                    'created_at' => $o->created_at->addDays(2),
                ]);
            }
        }

        // Sale Audit Logs
        foreach ($sales as $s) {
            AuditLog::create([
                'company_id' => $company->id,
                'user_id' => $s->user_id,
                'event' => 'Sale Completed',
                'description' => "Completed POS Sale Invoice #{$s->invoice_number} totaling ৳" . number_format($s->total_amount, 2),
                'ip_address' => '127.0.0.1',
                'created_at' => $s->created_at,
            ]);
        }

        // Warehouse Transfer Audit Logs
        foreach (array_slice($warehouses, 0, 5) as $wh) {
            AuditLog::create([
                'company_id' => $company->id,
                'user_id' => $wh->manager_id,
                'event' => 'Warehouse Updated',
                'description' => "Updated capacity and settings for warehouse '{$wh->name}'",
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subDays(rand(5, 15)),
            ]);
        }
    }
}
