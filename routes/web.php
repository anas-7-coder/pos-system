<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchUserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CashMovementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerReceivableController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductBarcodeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterSessionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxClassController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Companies
    Route::resource('companies', CompanyController::class);
    Route::get('companies-trash', [CompanyController::class, 'trash'])->name('companies.trash');
    Route::patch('companies/{company}/restore', [CompanyController::class, 'restore'])->name('companies.restore');
    Route::delete('companies/{company}/force-delete', [CompanyController::class, 'forceDelete'])->name('companies.force-delete');

    // Branches
    Route::resource('branches', BranchController::class);
    Route::get('branches-trash', [BranchController::class, 'trash'])->name('branches.trash');
    Route::patch('branches/{branch}/restore', [BranchController::class, 'restore'])->name('branches.restore');
    Route::delete('branches/{branch}/force-delete', [BranchController::class, 'forceDelete'])->name('branches.force-delete');

    // Warehouses
    Route::resource('warehouses', WarehouseController::class);
    Route::get('warehouses-trash', [WarehouseController::class, 'trash'])->name('warehouses.trash');
    Route::patch('warehouses/{warehouse}/restore', [WarehouseController::class, 'restore'])->name('warehouses.restore');
    Route::delete('warehouses/{warehouse}/force-delete', [WarehouseController::class, 'forceDelete'])->name('warehouses.force-delete');

    // Branch-Users
    Route::get('branches/{branch}/users', [BranchUserController::class, 'index'])->name('branches.users.index');
    Route::get('branches/{branch}/users/create', [BranchUserController::class, 'create'])->name('branches.users.create');
    Route::post('branches/{branch}/users', [BranchUserController::class, 'store'])->name('branches.users.store');
    Route::delete('branches/{branch}/users/{user}', [BranchUserController::class, 'destroy'])->name('branches.users.destroy');

    // Registers
    Route::resource('registers', RegisterController::class);
    Route::get('registers-trash', [RegisterController::class, 'trash'])->name('registers.trash');
    Route::patch('registers/{register}/restore', [RegisterController::class, 'restore'])->name('registers.restore');
    Route::delete('registers/{register}/force-delete', [RegisterController::class, 'forceDelete'])->name('registers.force-delete');

    // Register-Sessions
    Route::get('register-sessions', [RegisterSessionController::class, 'index'])->name('register-sessions.index');
    Route::get('register-sessions/create', [RegisterSessionController::class, 'create'])->name('register-sessions.create');
    Route::post('register-sessions', [RegisterSessionController::class, 'store'])->name('register-sessions.store');
    Route::get('register-sessions/{registerSession}', [RegisterSessionController::class, 'show'])->name('register-sessions.show');
    Route::patch('register-sessions/{registerSession}/close', [RegisterSessionController::class, 'close'])->name('register-sessions.close');

    // Category
    Route::resource('categories', CategoryController::class);
    Route::get('categories-trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::patch('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

    // Brands
    Route::resource('brands', BrandController::class);
    Route::get('brands-trash', [BrandController::class, 'trash'])->name('brands.trash');
    Route::patch('brands/{brand}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::delete('brands/{brand}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');

    // Units
    Route::resource('units', UnitController::class);
    Route::get('units-trash', [UnitController::class, 'trash'])->name('units.trash');
    Route::patch('units/{unit}/restore', [UnitController::class, 'restore'])->name('units.restore');
    Route::delete('units/{unit}/force-delete', [UnitController::class, 'forceDelete'])->name('units.force-delete');

    // Tax-Classes
    Route::resource('tax-classes', TaxClassController::class);
    Route::get('tax-classes-trash', [TaxClassController::class, 'trash'])->name('tax-classes.trash');
    Route::patch('tax-classes/{taxClass}/restore', [TaxClassController::class, 'restore'])->name('tax-classes.restore');
    Route::delete('tax-classes/{taxClass}/force-delete', [TaxClassController::class, 'forceDelete'])->name('tax-classes.force-delete');

    // Tax-Rates
    Route::resource('tax-rates', TaxRateController::class);
    Route::get('tax-rates-trash', [TaxRateController::class, 'trash'])->name('tax-rates.trash');
    Route::patch('tax-rates/{taxRate}/restore', [TaxRateController::class, 'restore'])->name('tax-rates.restore');
    Route::delete('tax-rates/{taxRate}/force-delete', [TaxRateController::class, 'forceDelete'])->name('tax-rates.force-delete');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products-trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    // Product-Barcodes
    Route::get('products/{product}/barcodes', [ProductBarcodeController::class, 'index'])->name('products.barcodes.index');
    Route::get('products/{product}/barcodes/create', [ProductBarcodeController::class, 'create'])->name('products.barcodes.create');
    Route::post('products/{product}/barcodes', [ProductBarcodeController::class, 'store'])->name('products.barcodes.store');
    Route::get('product-barcodes/{productBarcode}/edit', [ProductBarcodeController::class, 'edit'])->name('products.barcodes.edit');
    Route::put('product-barcodes/{productBarcode}', [ProductBarcodeController::class, 'update'])->name('products.barcodes.update');
    Route::delete('product-barcodes/{productBarcode}', [ProductBarcodeController::class, 'destroy'])->name('products.barcodes.destroy');
    Route::get('products/{product}/barcodes-trash', [ProductBarcodeController::class, 'trash'])->name('products.barcodes.trash');
    Route::patch('product-barcodes/{productBarcode}/restore', [ProductBarcodeController::class, 'restore'])->name('products.barcodes.restore');
    Route::delete('product-barcodes/{productBarcode}/force-delete', [ProductBarcodeController::class, 'forceDelete'])->name('products.barcodes.force-delete');

    // Product-Variants
    Route::get('products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants.index');
    Route::get('products/{product}/variants/create', [ProductVariantController::class, 'create'])->name('products.variants.create');
    Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::get('product-variants/{productVariant}', [ProductVariantController::class, 'show'])->name('products.variants.show');
    Route::get('product-variants/{productVariant}/edit', [ProductVariantController::class, 'edit'])->name('products.variants.edit');
    Route::put('product-variants/{productVariant}', [ProductVariantController::class, 'update'])->name('products.variants.update');
    Route::delete('product-variants/{productVariant}', [ProductVariantController::class, 'destroy'])->name('products.variants.destroy');
    Route::get('products/{product}/variants-trash', [ProductVariantController::class, 'trash'])->name('products.variants.trash');
    Route::patch('product-variants/{productVariant}/restore', [ProductVariantController::class, 'restore'])->name('products.variants.restore');
    Route::delete('product-variants/{productVariant}/force-delete', [ProductVariantController::class, 'forceDelete'])->name('products.variants.force-delete');

    // Product-Prices
    Route::get('/products/{product}/prices', [ProductPriceController::class, 'index'])->name('products.prices.index');
    Route::get('/products/{product}/prices/create', [ProductPriceController::class, 'create'])->name('products.prices.create');
    Route::post('/products/{product}/prices', [ProductPriceController::class, 'store'])->name('products.prices.store');
    Route::get('/products/{product}/prices/{productPrice}', [ProductPriceController::class, 'show'])->name('products.prices.show');
    Route::get('/products/{product}/prices/{productPrice}/edit', [ProductPriceController::class, 'edit'])->name('products.prices.edit');
    Route::put('/products/{product}/prices/{productPrice}', [ProductPriceController::class, 'update'])->name('products.prices.update');
    Route::delete('/products/{product}/prices/{productPrice}', [ProductPriceController::class, 'destroy'])->name('products.prices.destroy');
    Route::get('/products/{product}/prices-trash', [ProductPriceController::class, 'trash'])->name('products.prices.trash');
    Route::patch('/products/{product}/prices/{productPrice}/restore', [ProductPriceController::class, 'restore'])->name('products.prices.restore');
    Route::delete('/products/{product}/prices/{productPrice}/force-delete', [ProductPriceController::class, 'forceDelete'])->name('products.prices.force-delete');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers-trash', [CustomerController::class, 'trash'])->name('customers.trash');
    Route::patch('/customers/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::delete('/customers/{customer}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');

    // Customer-Groups
    Route::get('/customer-groups', [CustomerGroupController::class, 'index'])->name('customer-groups.index');
    Route::get('/customer-groups/create', [CustomerGroupController::class, 'create'])->name('customer-groups.create');
    Route::post('/customer-groups', [CustomerGroupController::class, 'store'])->name('customer-groups.store');
    Route::get('/customer-groups/{customerGroup}', [CustomerGroupController::class, 'show'])->name('customer-groups.show');
    Route::get('/customer-groups/{customerGroup}/edit', [CustomerGroupController::class, 'edit'])->name('customer-groups.edit');
    Route::put('/customer-groups/{customerGroup}', [CustomerGroupController::class, 'update'])->name('customer-groups.update');
    Route::delete('/customer-groups/{customerGroup}', [CustomerGroupController::class, 'destroy'])->name('customer-groups.destroy');
    Route::get('/customer-groups-trash', [CustomerGroupController::class, 'trash'])->name('customer-groups.trash');
    Route::patch('/customer-groups/{customerGroup}/restore', [CustomerGroupController::class, 'restore'])->name('customer-groups.restore');
    Route::delete('/customer-groups/{customerGroup}/force-delete', [CustomerGroupController::class, 'forceDelete'])->name('customer-groups.force-delete');

    // Supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/suppliers-trash', [SupplierController::class, 'trash'])->name('suppliers.trash');
    Route::patch('/suppliers/{supplier}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');
    Route::delete('/suppliers/{supplier}/force-delete', [SupplierController::class, 'forceDelete'])->name('suppliers.force-delete');

    // Purchase-Order
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('/purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::patch('/purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
    Route::patch('/purchase-orders/{purchaseOrder}/submit', [PurchaseOrderController::class, 'submit'])->name('purchase-orders.submit');
    Route::patch('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');

    // Goods-Receipt
    Route::get('/goods-receipts', [GoodsReceiptController::class, 'index'])->name('goods-receipts.index');
    Route::get('/purchase-orders/{purchaseOrder}/goods-receipts/create', [GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
    Route::post('/purchase-orders/{purchaseOrder}/goods-receipts', [GoodsReceiptController::class, 'store'])->name('goods-receipts.store');
    Route::get('/goods-receipts/{goodsReceipt}', [GoodsReceiptController::class, 'show'])->name('goods-receipts.show');
    Route::get('/goods-receipts/{goodsReceipt}/edit', [GoodsReceiptController::class, 'edit'])->name('goods-receipts.edit');
    Route::put('/goods-receipts/{goodsReceipt}', [GoodsReceiptController::class, 'update'])->name('goods-receipts.update');
    Route::patch('/goods-receipts/{goodsReceipt}/post', [GoodsReceiptController::class, 'post'])->name('goods-receipts.post');
    Route::patch('/goods-receipts/{goodsReceipt}/cancel', [GoodsReceiptController::class, 'cancel'])->name('goods-receipts.cancel');
    Route::get('/purchase-orders/{purchaseOrder}/goods-receipts', [GoodsReceiptController::class, 'purchaseOrderIndex'])->name('purchase-orders.goods-receipts.index');

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::get('/inventory/adjust', [InventoryController::class, 'adjustmentCreate'])->name('inventory.adjust.create');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjustmentStore'])->name('inventory.adjust.store');
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/inventory/reorder-level', [InventoryController::class, 'reorderLevelCreate'])->name('inventory.reorder-level.create');
    Route::post('/inventory/reorder-level', [InventoryController::class, 'reorderLevelStore'])->name('inventory.reorder-level.store');

    // Stock Transfers
    Route::get('/stock-transfers', [StockTransferController::class, 'index'])->name('stock-transfers.index');
    Route::get('/stock-transfers/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
    Route::post('/stock-transfers', [StockTransferController::class, 'store'])->name('stock-transfers.store');
    Route::get('/stock-transfers/{stockTransfer}', [StockTransferController::class, 'show'])->name('stock-transfers.show');
    Route::get('/stock-transfers/{stockTransfer}/edit', [StockTransferController::class, 'edit'])->name('stock-transfers.edit');
    Route::put('/stock-transfers/{stockTransfer}', [StockTransferController::class, 'update'])->name('stock-transfers.update');
    Route::patch('/stock-transfers/{stockTransfer}/post', [StockTransferController::class, 'post'])->name('stock-transfers.post');
    Route::patch('/stock-transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])->name('stock-transfers.cancel');

    // Stock Count
    Route::get('/stock-counts', [StockCountController::class, 'index'])->name('stock-counts.index');
    Route::get('/stock-counts/create', [StockCountController::class, 'create'])->name('stock-counts.create');
    Route::post('/stock-counts', [StockCountController::class, 'store'])->name('stock-counts.store');
    Route::get('/stock-counts/{stockCount}', [StockCountController::class, 'show'])->name('stock-counts.show');
    Route::get('/stock-counts/{stockCount}/edit', [StockCountController::class, 'edit'])->name('stock-counts.edit');
    Route::put('/stock-counts/{stockCount}', [StockCountController::class, 'update'])->name('stock-counts.update');
    Route::patch('/stock-counts/{stockCount}/post', [StockCountController::class, 'post'])->name('stock-counts.post');
    Route::patch('/stock-counts/{stockCount}/cancel', [StockCountController::class, 'cancel'])->name('stock-counts.cancel');

    // Sales
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::patch('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    Route::patch('/sales/{sale}/credit', [SaleController::class, 'credit'])->middleware('permission:sales.credit')->name('sales.credit');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->middleware('permission:payments.view')->name('payments.index');
    Route::get('/sales/{sale}/payments/create', [PaymentController::class, 'create'])->middleware('permission:payments.create')->name('sales.payments.create');
    Route::post('/sales/{sale}/payments', [PaymentController::class, 'store'])->middleware('permission:payments.create')->name('sales.payments.store');

    // Cash Movement
    Route::get('/cash-movements', [CashMovementController::class, 'index'])->middleware('permission:cash_management.view')->name('cash-movements.index');
    Route::get('/cash-movements/create', [CashMovementController::class, 'create'])->middleware('permission:cash_management.create')->name('cash-movements.create');
    Route::post('/cash-movements', [CashMovementController::class, 'store'])->middleware('permission:cash_management.create')->name('cash-movements.store');

    // Sale Returns
    Route::get('/sale-returns', [SaleReturnController::class, 'index'])->middleware('permission:sales.view')->name('sale-returns.index');
    Route::get('/sales/{sale}/returns/create', [SaleReturnController::class, 'create'])->middleware('permission:sales.refund')->name('sales.returns.create');
    Route::post('/sales/{sale}/returns', [SaleReturnController::class, 'store'])->middleware('permission:sales.refund')->name('sales.returns.store');
    Route::get('/sale-returns/{saleReturn}', [SaleReturnController::class, 'show'])->middleware('permission:sales.view')->name('sale-returns.show');
    Route::patch('/sale-returns/{saleReturn}/complete', [SaleReturnController::class, 'complete'])->middleware('permission:sales.refund')->name('sale-returns.complete');
    Route::patch('/sale-returns/{saleReturn}/cancel', [SaleReturnController::class, 'cancel'])->middleware('permission:sales.refund')->name('sale-returns.cancel');

    // Refunds
    Route::get('/refunds', [RefundController::class, 'index'])->middleware('permission:sales.view')->name('refunds.index');
    Route::get('/sale-returns/{saleReturn}/refund/create', [RefundController::class, 'create'])->middleware('permission:sales.refund')->name('sale-returns.refunds.create');
    Route::post('/sale-returns/{saleReturn}/refund', [RefundController::class, 'store'])->middleware('permission:sales.refund')->name('sale-returns.refunds.store');

    // Customer Receivables
    Route::get('/customer-receivables', [CustomerReceivableController::class, 'index'])->middleware('permission:customer_receivables.view')->name('customer-receivables.index');
    Route::get('/customers/{customer}/receivables', [CustomerReceivableController::class, 'show'])->middleware('permission:customer_receivables.view')->name('customers.receivables');

    // Customer Payments
    Route::get('/customer-payments', [CustomerPaymentController::class, 'index'])->middleware('permission:customer_payments.view')->name('customer-payments.index');
    Route::get('/customers/{customer}/payments/create', [CustomerPaymentController::class, 'create'])->middleware('permission:customer_payments.create')->name('customers.payments.create');
    Route::post('/customers/{customer}/payments', [CustomerPaymentController::class, 'store'])->middleware('permission:customer_payments.create')->name('customers.payments.store');
    Route::get('/customer-payments/{customerPayment}', [CustomerPaymentController::class, 'show'])->middleware('permission:customer_payments.view')->name('customer-payments.show');

    // Users
    Route::get('users', [UserController::class, 'index'])->middleware('permission:users.view')->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->middleware('permission:users.create')->name('users.create');
    Route::post('users', [UserController::class, 'store'])->middleware('permission:users.create')->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('permission:users.view')->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.update')->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('permission:users.update')->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('users.destroy');
    Route::get('users-trash', [UserController::class, 'trash'])->middleware('permission:users.trash.view')->name('users.trash');
    Route::patch('users/{user}/restore', [UserController::class, 'restore'])->middleware('permission:users.restore')->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->middleware('permission:users.force_delete')->name('users.force-delete');

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->middleware('permission:sales.view')->name('reports.sales');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->middleware('permission:inventory.view')->name('reports.inventory');
    Route::get('/reports/purchasing', [ReportController::class, 'purchasing'])->middleware('permission:reports.view')->name('reports.purchasing');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->middleware('permission:reports.view')->name('reports.customers');
    Route::get('/reports/suppliers', [ReportController::class, 'suppliers'])->middleware('permission:reports.view')->name('reports.suppliers');
    Route::get('/reports/profitability', [ReportController::class, 'profitability'])->middleware('permission:reports.view')->name('reports.profitability');

});

// Route::get('/sales/create', function () {
//     return 'Create Sale Page';
// })->middleware(['auth','permission:sales.create']);

require __DIR__.'/auth.php';