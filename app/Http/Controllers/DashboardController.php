<?php

namespace App\Http\Controllers;

use App\Models\CustomerLedgerEntry;
use App\Models\Payment;
use App\Models\RegisterSession;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockBalance;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branchIds = $user->accessibleBranchIds();

        /*
        |--------------------------------------------------------------------------
        | Today's Sales
        |--------------------------------------------------------------------------
        */

        $todaySalesQuery = Sale::query()
            ->whereIn('branch_id', $branchIds)
            ->whereDate('sale_date', today())
            ->whereNotIn('status', ['draft', 'cancelled']);

        $todaySales = (float) $todaySalesQuery->sum('grand_total');

        $todayInvoices = (clone $todaySalesQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Receivables
        |--------------------------------------------------------------------------
        */

        $receivables = (float) CustomerLedgerEntry::query()
            ->whereIn('branch_id', $branchIds)
            ->selectRaw(
                'COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance'
            )
            ->value('balance');

        /*
        |--------------------------------------------------------------------------
        | Low Stock
        |--------------------------------------------------------------------------
        */

        $lowStockCount = StockBalance::query()
            ->whereHas('warehouse', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->where('reorder_level', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Open Register
        |--------------------------------------------------------------------------
        */

        $openRegister = RegisterSession::query()
            ->with(['register', 'branch'])
            ->where('user_id', $user->id)
            ->whereIn('branch_id', $branchIds)
            ->where('status', 'Open')
            ->latest('opened_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Recent Sales
        |--------------------------------------------------------------------------
        */

        $recentSales = Sale::query()
            ->with(['customer', 'branch'])
            ->whereIn('branch_id', $branchIds)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->latest('sale_date')
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Last 7 Days Sales
        |--------------------------------------------------------------------------
        */

        $salesLast7Days = Sale::query()
            ->whereIn('branch_id', $branchIds)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->whereDate('sale_date', '>=', today()->subDays(6))
            ->selectRaw('DATE(sale_date) as sale_day, SUM(grand_total) as total')
            ->groupBy('sale_day')
            ->orderBy('sale_day')
            ->get();

        $salesChartLabels = [];
        $salesChartValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);

            $salesChartLabels[] = $date->format('d M');

            $salesChartValues[] = (float) (
                $salesLast7Days
                    ->firstWhere('sale_day', $date->format('Y-m-d'))
                    ?->total ?? 0
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method Breakdown
        |--------------------------------------------------------------------------
        */

        $paymentBreakdown = Payment::query()
            ->where('status', 'posted')
            ->whereHas('sale', function ($query) use ($branchIds) {
                $query
                    ->whereIn('branch_id', $branchIds)
                    ->whereNotIn('status', ['draft', 'cancelled']);
            })
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Top Selling Products
        |--------------------------------------------------------------------------
        */

        $topProducts = SaleItem::query()
            ->selectRaw('
                product_id,
                SUM(quantity) as total_quantity,
                SUM(line_total) as total_sales
            ')
            ->with('product')
            ->whereHas('sale', function ($query) use ($branchIds) {
                $query
                    ->whereIn('branch_id', $branchIds)
                    ->whereNotIn('status', ['draft', 'cancelled']);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Branch Sales
        |--------------------------------------------------------------------------
        */

        $branchSales = Sale::query()
            ->with('branch')
            ->whereIn('branch_id', $branchIds)
            ->whereDate('sale_date', today())
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->selectRaw('branch_id, SUM(grand_total) as total_sales')
            ->groupBy('branch_id')
            ->orderByDesc('total_sales')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Branch Count
        |--------------------------------------------------------------------------
        */

        $branchCount = count($branchIds);

        return view('dashboard', [
            'todaySales' => $todaySales,
            'todayInvoices' => $todayInvoices,
            'receivables' => $receivables,
            'lowStockCount' => $lowStockCount,
            'openRegister' => $openRegister,
            'recentSales' => $recentSales,

            'salesChartLabels' => $salesChartLabels,
            'salesChartValues' => $salesChartValues,

            'paymentBreakdown' => $paymentBreakdown,
            'topProducts' => $topProducts,
            'branchSales' => $branchSales,

            'branchCount' => $branchCount,
        ]);
    }
}