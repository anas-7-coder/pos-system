<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('name', 'super_admin')->firstOrFail();
        $admin = Role::where('name', 'admin')->firstOrFail();
        $manager = Role::where('name', 'manager')->firstOrFail();
        $cashier = Role::where('name', 'cashier')->firstOrFail();
        $inventoryManager = Role::where('name', 'inventory_manager')->firstOrFail();
        $accountant = Role::where('name', 'accountant')->firstOrFail();

        // All Permissions
        $allPermissions = Permission::pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin->permissions()->sync($allPermissions);

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin->permissions()->sync($allPermissions);

        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        */

        $manager->permissions()->sync(
            Permission::whereIn('name', [

                'dashboard.view',

                // Companies
                'companies.view',
                'companies.create',
                'companies.update',
                'companies.delete',
                'companies.trash.view',
                'companies.restore',
                'companies.force_delete',

                // Baranches
                'branches.view',
                'branches.create',
                'branches.update',
                'branches.delete',
                'branches.trash.view',
                'branches.restore',
                'branches.force_delete',

                'branches.users.view',
                'branches.users.assign',
                'branches.users.remove',

                // Warehouses
                'warehouses.view',
                'warehouses.create',
                'warehouses.update',
                'warehouses.delete',
                'warehouses.trash.view',
                'warehouses.restore',
                'warehouses.force_delete',

                // Registers
                'registers.view',
                'registers.create',
                'registers.update',
                'registers.delete',
                'registers.trash.view',
                'registers.restore',
                'registers.force_delete',

                // Register Sessions
                'register_sessions.view',
                'register_sessions.open',
                'register_sessions.close',

                // Categories
                'categories.view',
                'categories.create',
                'categories.update',
                'categories.delete',
                'categories.trash.view',
                'categories.restore',
                'categories.force_delete',

                // Brands
                'brands.view',
                'brands.create',
                'brands.update',
                'brands.delete',
                'brands.trash.view',
                'brands.restore',
                'brands.force_delete',

                // Units
                'units.view',
                'units.create',
                'units.update',
                'units.delete',
                'units.trash.view',
                'units.restore',
                'units.force_delete',

                // Tax Classes
                'tax_classes.view',
                'tax_classes.create',
                'tax_classes.update',
                'tax_classes.delete',
                'tax_classes.trash.view',
                'tax_classes.restore',
                'tax_classes.force_delete',

                // Tax Rates
                'tax_rates.view',
                'tax_rates.create',
                'tax_rates.update',
                'tax_rates.delete',
                'tax_rates.trash.view',
                'tax_rates.restore',
                'tax_rates.force_delete',

                // Products
                'products.view',
                'products.create',
                'products.update',
                'products.delete',
                'products.trash.view',
                'products.restore',
                'products.force_delete',

                // Product Barcodes
                'product_barcodes.view',
                'product_barcodes.create',
                'product_barcodes.update',
                'product_barcodes.delete',
                'product_barcodes.trash.view',
                'product_barcodes.restore',
                'product_barcodes.force_delete',

                // Product Variants
                'product_variants.view',
                'product_variants.create',
                'product_variants.update',
                'product_variants.delete',
                'product_variants.trash.view',
                'product_variants.restore',
                'product_variants.force_delete',

                // Product Prices
                'product_prices.view',
                'product_prices.create',
                'product_prices.update',
                'product_prices.delete',
                'product_prices.trash.view',
                'product_prices.trash.restore',
                'product_prices.trash.force_delete',

                // Customers
                'customers.view',
                'customers.create',
                'customers.update',
                'customers.delete',
                'customers.trash.view',
                'customers.trash.restore',
                'customers.trash.force_delete',

                // Customer Groups
                'customer_groups.view',
                'customer_groups.create',
                'customer_groups.update',
                'customer_groups.delete',
                'customer_groups.trash.view',
                'customer_groups.trash.restore',
                'customer_groups.trash.force_delete',

                // Suppliers
                'suppliers.view',
                'suppliers.create',
                'suppliers.update',
                'suppliers.delete',
                'suppliers.trash.view',
                'suppliers.trash.restore',
                'suppliers.trash.force_delete',

                // Purchase Orders
                'purchase_orders.view',
                'purchase_orders.create',
                'purchase_orders.update',
                'purchase_orders.cancel',
                'purchase_orders.submit',
                'purchase_orders.approve',

                // Goods Receipt
                'goods_receipts.view',
                'goods_receipts.create',
                'goods_receipts.update',
                'goods_receipts.post',
                'goods_receipts.cancel',

                // Stock Transfers
                'stock_transfers.view',
                'stock_transfers.create',
                'stock_transfers.update',
                'stock_transfers.post',
                'stock_transfers.cancel',

                // Stock Count
                'stock_counts.view',
                'stock_counts.create',
                'stock_counts.update',
                'stock_counts.post',
                'stock_counts.cancel',

                // Sales
                'sales.view',
                'sales.create',
                'sales.cancel',
                'sales.refund',
                'sales.credit',

                // Inventory
                'inventory.view',
                'inventory.adjust',

                // Payments
                'payments.view',
                'payments.create',

                // Cash Movement
                'cash_management.view',
                'cash_management.create',

                // Customer Receivable
                'customer_receivables.view',

                // Customer Payments
                'customer_payments.view',
                'customer_payments.create',

                // Users
                'users.view',

                // Reports
                'reports.view',

            ])->pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Cashier
        |--------------------------------------------------------------------------
        */

        $cashier->permissions()->sync(
            Permission::whereIn('name', [

                'dashboard.view',

                // Products
                'products.view',
                'product_barcodes.view',
                'product_variants.view',
                'product_prices.view',

                // Customers
                'customers.view',
                'customers.create',
                'customers.update',

                // Customer Groups
                'customer_groups.view',

                // Registers
                'registers.view',

                // Register Sessions
                'register_sessions.view',
                'register_sessions.open',
                'register_sessions.close',

                // Sales
                'sales.view',
                'sales.create',
                'sales.credit',

                // Payments
                'payments.view',
                'payments.create',

                // Cash Movement
                'cash_management.view',
                'cash_management.create',

                // Customer Receivable
                'customer_receivables.view',

                // Customer Payments
                'customer_payments.view',
                'customer_payments.create',

            ])->pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Inventory Manager
        |--------------------------------------------------------------------------
        */

        $inventoryManager->permissions()->sync(
            Permission::whereIn('name', [

                'dashboard.view',

                // Products
                'products.view',
                'products.create',
                'products.update',

                // Product Barcodes
                'product_barcodes.view',
                'product_barcodes.create',
                'product_barcodes.update',

                // Product Variants
                'product_variants.view',
                'product_variants.create',
                'product_variants.update',

                // Suppliers
                'suppliers.view',
                'suppliers.create',
                'suppliers.update',

                // Purchase Orders
                'purchase_orders.view',
                'purchase_orders.create',
                'purchase_orders.update',
                'purchase_orders.submit',

                // Goods Receipt
                'goods_receipts.view',
                'goods_receipts.create',
                'goods_receipts.update',
                'goods_receipts.post',
                'goods_receipts.cancel',

                // Inventory
                'inventory.view',
                'inventory.adjust',

                // Stock Transfers
                'stock_transfers.view',
                'stock_transfers.create',
                'stock_transfers.update',
                'stock_transfers.post',
                'stock_transfers.cancel',

                // Stock Count
                'stock_counts.view',
                'stock_counts.create',
                'stock_counts.update',
                'stock_counts.post',
                'stock_counts.cancel',

            ])->pluck('id')
        );

        /*
        |--------------------------------------------------------------------------
        | Accountant
        |--------------------------------------------------------------------------
        */

        $accountant->permissions()->sync(
            Permission::whereIn('name', [

                'dashboard.view',

                // Sales
                'sales.view',

                // Customers
                'customers.view',

                // Suppliers
                'suppliers.view',

                // Purchase Orders
                'purchase_orders.view',

                // Good Recipts
                'goods_receipts.view',

                // Stock Transfers
                'stock_transfers.view',

                // Stock Count
                'stock_counts.view',

                // Payments
                'payments.view',

                // Cash Movement
                'cash_management.view',

                // Customer Receivable
                'customer_receivables.view',

                // Customer Payments
                'customer_payments.view',
                'customer_payments.create',

                // Reports
                'reports.view',

            ])->pluck('id')
        );
    }
}