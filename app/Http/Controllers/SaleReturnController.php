<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleReturnRequest;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Services\SaleReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SaleReturnController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', SaleReturn::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $returns = SaleReturn::query()->with(['sale', 'branch', 'warehouse', 'createdBy'])
            ->whereIn('branch_id', $user->accessibleBranchIds())
            ->latest('return_date')
            ->paginate(20);

        return view('sale-returns.index', compact('returns'));
    }

    public function create(Sale $sale): View
    {
        $this->authorize('create', [SaleReturn::class, $sale]);
        $sale->load(['items.product', 'items.productVariant', 'items.returnItems.saleReturn']);

        foreach ($sale->items as $item) {
            $item->returned_quantity = $item->returnItems
                ->filter(fn ($returnItem) => $returnItem->saleReturn && $returnItem->saleReturn->status === 'completed')
                ->sum('quantity');

            $item->remaining_quantity = max(0, (float) $item->quantity - (float) $item->returned_quantity);
        }

        return view('sale-returns.create', compact('sale'));
    }

    public function store(StoreSaleReturnRequest $request, Sale $sale, SaleReturnService $saleReturnService): RedirectResponse
    {
        $this->authorize('create', [SaleReturn::class, $sale]);

        try {
            $saleReturn = $saleReturnService->createDraft($sale, $request->validated(), Auth::user());

            return redirect()->route('sale-returns.show', $saleReturn)->with('success', 'Return draft created successfully.');
        } catch (\Throwable $e) {
            Log::error('Sale return creation failed', [
                'sale_id' => $sale->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(SaleReturn $saleReturn): View
    {
        $this->authorize('view', $saleReturn);
        $saleReturn->load(['sale', 'branch', 'warehouse', 'createdBy', 'completedBy', 'items.product', 'items.productVariant', 'refund']);

        return view('sale-returns.show', compact('saleReturn'));
    }

    public function complete(SaleReturn $saleReturn, SaleReturnService $saleReturnService): RedirectResponse
    {
        $this->authorize('complete', $saleReturn);

        try {
            $saleReturnService->complete($saleReturn, Auth::user());

            return redirect()->route('sale-returns.show', $saleReturn)->with('success', 'Sale return completed and inventory restored.');
        } catch (\Throwable $e) {
            Log::error('Sale return completion failed', [
                'sale_return_id' => $saleReturn->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(SaleReturn $saleReturn, SaleReturnService $saleReturnService): RedirectResponse
    {
        $this->authorize('cancel', $saleReturn);

        try {
            $saleReturnService->cancel($saleReturn);

            return redirect()->route('sale-returns.show', $saleReturn)->with('success', 'Sale return cancelled successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
