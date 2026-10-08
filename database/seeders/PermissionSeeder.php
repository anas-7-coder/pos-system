<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],

            ['name' => 'users.view',         'display_name' => 'View Users',               'module' => 'users'],
            ['name' => 'users.create',       'display_name' => 'Create Users',             'module' => 'users'],
            ['name' => 'users.update',       'display_name' => 'Update Users',             'module' => 'users'],
            ['name' => 'users.delete',       'display_name' => 'Delete Users',             'module' => 'users'],
            ['name' => 'users.trash.view',   'display_name' => 'View User Trash',          'module' => 'users'],
            ['name' => 'users.restore',      'display_name' => 'Restore Users',            'module' => 'users'],
            ['name' => 'users.force_delete', 'display_name' => 'Permanently Delete Users', 'module' => 'users'],

            ['name' => 'companies.view',         'display_name' => 'View Companies',               'module' => 'companies'],
            ['name' => 'companies.create',       'display_name' => 'Create Companies',             'module' => 'companies'],
            ['name' => 'companies.update',       'display_name' => 'Update Companies',             'module' => 'companies'],
            ['name' => 'companies.delete',       'display_name' => 'Delete Companies',             'module' => 'companies'],
            ['name' => 'companies.trash.view',   'display_name' => 'View Company Trash',           'module' => 'companies'],
            ['name' => 'companies.restore',      'display_name' => 'Restore Companies',            'module' => 'companies'],
            ['name' => 'companies.force_delete', 'display_name' => 'Permanently Delete Companies', 'module' => 'companies'],

            ['name' => 'branches.view',         'display_name' => 'View Branches',               'module' => 'branches'],
            ['name' => 'branches.create',       'display_name' => 'Create Branches',             'module' => 'branches'],
            ['name' => 'branches.update',       'display_name' => 'Update Branches',             'module' => 'branches'],
            ['name' => 'branches.delete',       'display_name' => 'Delete Branches',             'module' => 'branches'],
            ['name' => 'branches.trash.view',   'display_name' => 'View Branch Trash',           'module' => 'branches'],
            ['name' => 'branches.restore',      'display_name' => 'Restore Branches',            'module' => 'branches'],
            ['name' => 'branches.force_delete', 'display_name' => 'Permanently Delete Branches', 'module' => 'branches'],

            ['name' => 'warehouses.view',         'display_name' => 'View Warehouses',               'module' => 'warehouses'],
            ['name' => 'warehouses.create',       'display_name' => 'Create Warehouses',             'module' => 'warehouses'],
            ['name' => 'warehouses.update',       'display_name' => 'Update Warehouses',             'module' => 'warehouses'],
            ['name' => 'warehouses.delete',       'display_name' => 'Delete Warehouses',             'module' => 'warehouses'],
            ['name' => 'warehouses.trash.view',   'display_name' => 'View Warehouse Trash',          'module' => 'warehouses'],
            ['name' => 'warehouses.restore',      'display_name' => 'Restore Warehouses',            'module' => 'warehouses'],
            ['name' => 'warehouses.force_delete', 'display_name' => 'Permanently Delete Warehouses', 'module' => 'warehouses'],

            ['name' => 'branches.users.view',   'display_name' => 'View Branch Users',          'module' => 'branches'],
            ['name' => 'branches.users.assign', 'display_name' => 'Assign Users to Branches',   'module' => 'branches'],
            ['name' => 'branches.users.remove', 'display_name' => 'Remove Users from Branches', 'module' => 'branches'],

            ['name' => 'registers.view',         'display_name' => 'View Registers',               'module' => 'registers'],
            ['name' => 'registers.create',       'display_name' => 'Create Registers',             'module' => 'registers'],
            ['name' => 'registers.update',       'display_name' => 'Update Registers',             'module' => 'registers'],
            ['name' => 'registers.delete',       'display_name' => 'Delete Registers',             'module' => 'registers'],
            ['name' => 'registers.trash.view',   'display_name' => 'View Register Trash',          'module' => 'registers'],
            ['name' => 'registers.restore',      'display_name' => 'Restore Registers',            'module' => 'registers'],
            ['name' => 'registers.force_delete', 'display_name' => 'Permanently Delete Registers', 'module' => 'registers'],

            ['name' => 'register_sessions.view',  'display_name' => 'View Register Sessions',  'module' => 'register_sessions'],
            ['name' => 'register_sessions.open',  'display_name' => 'Open Register Sessions',  'module' => 'register_sessions'],
            ['name' => 'register_sessions.close', 'display_name' => 'Close Register Sessions', 'module' => 'register_sessions'],

            ['name' => 'categories.view',         'display_name' => 'View Categories',               'module' => 'categories'],
            ['name' => 'categories.create',       'display_name' => 'Create Categories',             'module' => 'categories'],
            ['name' => 'categories.update',       'display_name' => 'Update Categories',             'module' => 'categories'],
            ['name' => 'categories.delete',       'display_name' => 'Delete Categories',             'module' => 'categories'],
            ['name' => 'categories.trash.view',   'display_name' => 'View Category Trash',           'module' => 'categories'],
            ['name' => 'categories.restore',      'display_name' => 'Restore Categories',            'module' => 'categories'],
            ['name' => 'categories.force_delete', 'display_name' => 'Permanently Delete Categories', 'module' => 'categories'],

            ['name' => 'brands.view',         'display_name' => 'View Brands',               'module' => 'brands'],
            ['name' => 'brands.create',       'display_name' => 'Create Brands',             'module' => 'brands'],
            ['name' => 'brands.update',       'display_name' => 'Update Brands',             'module' => 'brands'],
            ['name' => 'brands.delete',       'display_name' => 'Delete Brands',             'module' => 'brands'],
            ['name' => 'brands.trash.view',   'display_name' => 'View Brand Trash',          'module' => 'brands'],
            ['name' => 'brands.restore',      'display_name' => 'Restore Brands',            'module' => 'brands'],
            ['name' => 'brands.force_delete', 'display_name' => 'Permanently Delete Brands', 'module' => 'brands'],

            ['name' => 'units.view',         'display_name' => 'View Units',               'module' => 'units'],
            ['name' => 'units.create',       'display_name' => 'Create Units',             'module' => 'units'],
            ['name' => 'units.update',       'display_name' => 'Update Units',             'module' => 'units'],
            ['name' => 'units.delete',       'display_name' => 'Delete Units',             'module' => 'units'],
            ['name' => 'units.trash.view',   'display_name' => 'View Unit Trash',          'module' => 'units'],
            ['name' => 'units.restore',      'display_name' => 'Restore Units',            'module' => 'units'],
            ['name' => 'units.force_delete', 'display_name' => 'Permanently Delete Units', 'module' => 'units'],

            ['name' => 'tax_classes.view',         'display_name' => 'View Tax Classes',               'module' => 'tax_classes'],
            ['name' => 'tax_classes.create',       'display_name' => 'Create Tax Classes',             'module' => 'tax_classes'],
            ['name' => 'tax_classes.update',       'display_name' => 'Update Tax Classes',             'module' => 'tax_classes'],
            ['name' => 'tax_classes.delete',       'display_name' => 'Delete Tax Classes',             'module' => 'tax_classes'],
            ['name' => 'tax_classes.trash.view',   'display_name' => 'View Tax Class Trash',           'module' => 'tax_classes'],
            ['name' => 'tax_classes.restore',      'display_name' => 'Restore Tax Classes',            'module' => 'tax_classes'],
            ['name' => 'tax_classes.force_delete', 'display_name' => 'Permanently Delete Tax Classes', 'module' => 'tax_classes'],

            ['name' => 'tax_rates.view',         'display_name' => 'View Tax Rates',               'module' => 'tax_rates'],
            ['name' => 'tax_rates.create',       'display_name' => 'Create Tax Rates',             'module' => 'tax_rates'],
            ['name' => 'tax_rates.update',       'display_name' => 'Update Tax Rates',             'module' => 'tax_rates'],
            ['name' => 'tax_rates.delete',       'display_name' => 'Delete Tax Rates',             'module' => 'tax_rates'],
            ['name' => 'tax_rates.trash.view',   'display_name' => 'View Tax Rate Trash',          'module' => 'tax_rates'],
            ['name' => 'tax_rates.restore',      'display_name' => 'Restore Tax Rates',            'module' => 'tax_rates'],
            ['name' => 'tax_rates.force_delete', 'display_name' => 'Permanently Delete Tax Rates', 'module' => 'tax_rates'],

            ['name' => 'products.view',         'display_name' => 'View Products',               'module' => 'products'],
            ['name' => 'products.create',       'display_name' => 'Create Products',             'module' => 'products'],
            ['name' => 'products.update',       'display_name' => 'Update Products',             'module' => 'products'],
            ['name' => 'products.delete',       'display_name' => 'Delete Products',             'module' => 'products'],
            ['name' => 'products.trash.view',   'display_name' => 'View Product Trash',          'module' => 'products'],
            ['name' => 'products.restore',      'display_name' => 'Restore Products',            'module' => 'products'],
            ['name' => 'products.force_delete', 'display_name' => 'Permanently Delete Products', 'module' => 'products'],

            ['name' => 'product_barcodes.view',         'display_name' => 'View Product Barcodes',               'module' => 'product_barcodes'],
            ['name' => 'product_barcodes.create',       'display_name' => 'Create Product Barcodes',             'module' => 'product_barcodes'],
            ['name' => 'product_barcodes.update',       'display_name' => 'Update Product Barcodes',             'module' => 'product_barcodes'],
            ['name' => 'product_barcodes.delete',       'display_name' => 'Delete Product Barcodes',             'module' => 'product_barcodes'],
            ['name' => 'product_barcodes.trash.view',   'display_name' => 'View Barcode Trash',                  'module' => 'product_barcodes'],
            ['name' => 'product_barcodes.restore',      'display_name' => 'Restore Product Barcodes',            'module' => 'product_barcodes'],
            ['name' => 'product_barcodes.force_delete', 'display_name' => 'Permanently Delete Product Barcodes', 'module' => 'product_barcodes'],

            ['name' => 'product_variants.view',         'display_name' => 'View Product Variants',               'module' => 'product_variants'],
            ['name' => 'product_variants.create',       'display_name' => 'Create Product Variants',             'module' => 'product_variants'],
            ['name' => 'product_variants.update',       'display_name' => 'Update Product Variants',             'module' => 'product_variants'],
            ['name' => 'product_variants.delete',       'display_name' => 'Delete Product Variants',             'module' => 'product_variants'],
            ['name' => 'product_variants.trash.view',   'display_name' => 'View Product Variant Trash',          'module' => 'product_variants'],
            ['name' => 'product_variants.restore',      'display_name' => 'Restore Product Variants',            'module' => 'product_variants'],
            ['name' => 'product_variants.force_delete', 'display_name' => 'Permanently Delete Product Variants', 'module' => 'product_variants'],

            ['name' => 'product_prices.view',               'display_name' => 'View Product Prices',               'module' => 'product_prices'],
            ['name' => 'product_prices.create',             'display_name' => 'Create Product Prices',             'module' => 'product_prices'],
            ['name' => 'product_prices.update',             'display_name' => 'Update Product Prices',             'module' => 'product_prices'],
            ['name' => 'product_prices.delete',             'display_name' => 'Delete Product Prices',             'module' => 'product_prices'],
            ['name' => 'product_prices.trash.view',         'display_name' => 'View Product Prices Trash',         'module' => 'product_prices'],
            ['name' => 'product_prices.trash.restore',      'display_name' => 'Restore Product Prices',            'module' => 'product_prices'],
            ['name' => 'product_prices.trash.force_delete', 'display_name' => 'Permanently Delete Product Prices', 'module' => 'product_prices'],

            ['name' => 'customers.view',               'display_name' => 'View Customers',               'module' => 'customers'],
            ['name' => 'customers.create',             'display_name' => 'Create Customers',             'module' => 'customers'],
            ['name' => 'customers.update',             'display_name' => 'Update Customers',             'module' => 'customers'],
            ['name' => 'customers.delete',             'display_name' => 'Delete Customers',             'module' => 'customers'],
            ['name' => 'customers.trash.view',         'display_name' => 'View Customers Trash',         'module' => 'customers'],
            ['name' => 'customers.trash.restore',      'display_name' => 'Restore Customers',            'module' => 'customers'],
            ['name' => 'customers.trash.force_delete', 'display_name' => 'Permanently Delete Customers', 'module' => 'customers'],

            ['name' => 'customer_groups.view',               'display_name' => 'View Customer Groups',               'module' => 'customer_groups'],
            ['name' => 'customer_groups.create',             'display_name' => 'Create Customer Groups',             'module' => 'customer_groups'],
            ['name' => 'customer_groups.update',             'display_name' => 'Update Customer Groups',             'module' => 'customer_groups'],
            ['name' => 'customer_groups.delete',             'display_name' => 'Delete Customer Groups',             'module' => 'customer_groups'],
            ['name' => 'customer_groups.trash.view',         'display_name' => 'View Customer Groups Trash',         'module' => 'customer_groups'],
            ['name' => 'customer_groups.trash.restore',      'display_name' => 'Restore Customer Groups',            'module' => 'customer_groups'],
            ['name' => 'customer_groups.trash.force_delete', 'display_name' => 'Permanently Delete Customer Groups', 'module' => 'customer_groups'],

            ['name' => 'suppliers.view',               'display_name' => 'View Suppliers',               'module' => 'suppliers'],
            ['name' => 'suppliers.create',             'display_name' => 'Create Suppliers',             'module' => 'suppliers'],
            ['name' => 'suppliers.update',             'display_name' => 'Update Suppliers',             'module' => 'suppliers'],
            ['name' => 'suppliers.delete',             'display_name' => 'Delete Suppliers',             'module' => 'suppliers'],
            ['name' => 'suppliers.trash.view',         'display_name' => 'View Supplier Trash',          'module' => 'suppliers'],
            ['name' => 'suppliers.trash.restore',      'display_name' => 'Restore Suppliers',            'module' => 'suppliers'],
            ['name' => 'suppliers.trash.force_delete', 'display_name' => 'Permanently Delete Suppliers', 'module' => 'suppliers'],

            ['name' => 'purchase_orders.view',    'display_name' => 'View Purchase Orders',    'module' => 'purchase_orders'],
            ['name' => 'purchase_orders.create',  'display_name' => 'Create Purchase Orders',  'module' => 'purchase_orders'],
            ['name' => 'purchase_orders.update',  'display_name' => 'Update Purchase Orders',  'module' => 'purchase_orders'],
            ['name' => 'purchase_orders.cancel',  'display_name' => 'Cancel Purchase Orders',  'module' => 'purchase_orders'],
            ['name' => 'purchase_orders.submit',  'display_name' => 'Submit Purchase Orders',  'module' => 'purchase_orders'],
            ['name' => 'purchase_orders.approve', 'display_name' => 'Approve Purchase Orders', 'module' => 'purchase_orders'],

            ['name' => 'goods_receipts.view',     'display_name' => 'View Goods Receipts',     'module' => 'goods_receipts'],
            ['name' => 'goods_receipts.create',   'display_name' => 'Create Goods Receipts',   'module' => 'goods_receipts'],
            ['name' => 'goods_receipts.update',   'display_name' => 'Update Goods Receipts',   'module' => 'goods_receipts'],
            ['name' => 'goods_receipts.post',     'display_name' => 'Post Goods Receipts',     'module' => 'goods_receipts'],
            ['name' => 'goods_receipts.cancel',   'display_name' => 'Cancel Goods Receipts',   'module' => 'goods_receipts'],

            ['name' => 'stock_transfers.view',   'display_name' => 'View Stock Transfers',   'module' => 'stock_transfers'],
            ['name' => 'stock_transfers.create', 'display_name' => 'Create Stock Transfers', 'module' => 'stock_transfers'],
            ['name' => 'stock_transfers.update', 'display_name' => 'Update Stock Transfers', 'module' => 'stock_transfers'],
            ['name' => 'stock_transfers.post',   'display_name' => 'Post Stock Transfers',   'module' => 'stock_transfers'],
            ['name' => 'stock_transfers.cancel', 'display_name' => 'Cancel Stock Transfers', 'module' => 'stock_transfers'],

            ['name' => 'stock_counts.view',   'display_name' => 'View Stock Counts',   'module' => 'stock_counts'],
            ['name' => 'stock_counts.create', 'display_name' => 'Create Stock Counts', 'module' => 'stock_counts'],
            ['name' => 'stock_counts.update', 'display_name' => 'Update Stock Counts', 'module' => 'stock_counts'],
            ['name' => 'stock_counts.post',   'display_name' => 'Post Stock Counts',   'module' => 'stock_counts'],
            ['name' => 'stock_counts.cancel', 'display_name' => 'Cancel Stock Counts', 'module' => 'stock_counts'],

            ['name' => 'sales.view',   'display_name' => 'View Sales',            'module' => 'sales'],
            ['name' => 'sales.create', 'display_name' => 'Create Sales',          'module' => 'sales'],
            ['name' => 'sales.cancel', 'display_name' => 'Cancel Sales',          'module' => 'sales'],
            ['name' => 'sales.refund', 'display_name' => 'Refund Sales',          'module' => 'sales'],
            ['name' => 'sales.credit', 'display_name' => 'Complete Credit Sales', 'module' => 'sales'],

            ['name' => 'payments.view',   'display_name' => 'View Payments',   'module' => 'payments'],
            ['name' => 'payments.create', 'display_name' => 'Create Payments', 'module' => 'payments'],

            ['name' => 'inventory.view',   'display_name' => 'View Inventory',   'module' => 'inventory'],
            ['name' => 'inventory.adjust', 'display_name' => 'Adjust Inventory', 'module' => 'inventory'],

            ['name' => 'cash_management.view',   'display_name' => 'View Cash Management',  'module' => 'cash_management'],
            ['name' => 'cash_management.create', 'display_name' => 'Create Cash Movements', 'module' => 'cash_management'],
            ['name' => 'reports.view',           'display_name' => 'View Reports',          'module' => 'reports'],

            ['name' => 'customer_receivables.view', 'display_name' => 'View Customer Receivables', 'module' => 'customer_receivables'],

            ['name' => 'customer_payments.view',   'display_name' => 'View Customer Payments',   'module' => 'customer_payments'],
            ['name' => 'customer_payments.create', 'display_name' => 'Create Customer Payments', 'module' => 'customer_payments'],

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
