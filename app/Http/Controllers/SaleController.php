<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\RegisterSession;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\CustomerReceivableService;
use App\Services\SaleCompletionService;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(private SaleService $saleService) {}

    private function ensureBranchAccess(Sale $sale): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->canAccessBranch((int) $sale->branch_id), 403);
    }

    public function index(): View
    {
        $this->authorize('viewAny', Sale::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sales = Sale::query()
            ->whereIn('branch_id', $user->accessibleBranchIds())
            ->with(['customer', 'branch', 'warehouse', 'register', 'cashier'])
            ->latest('sale_date')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Sale::class);
        $user = $request->user();

        $customers = Customer::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $sessions = RegisterSession::query()
            ->with('register')
            ->where('status', 'Open')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
            )
            ->latest('opened_at')
            ->get();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereIn('branch_id', $user->branches()
                    ->pluck('branches.id')
                );
            }
            )
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 'Active')
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();

        $variants = ProductVariant::query()
            ->where('status', 'Active')
            ->with('product')
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('customers', 'sessions', 'warehouses', 'products', 'variants'));
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        try {
            $sale = $this->saleService->createDraft($request->user(), $request->validated());

            return redirect()->route('sales.show', $sale)->with('success', 'Draft sale created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to create sale.');
        }
    }

    public function show(Sale $sale, CustomerReceivableService $customerReceivableService): View
    {
        $this->ensureBranchAccess($sale);
        $this->authorize('view', $sale);
        $sale->load(['customer', 'branch', 'warehouse', 'register', 'registerSession', 'cashier', 'items.product', 'items.productVariant', 'payments' => fn ($query) => $query->where('status', 'posted')->latest('paid_at'),  'returns.refund']);
        $customerBalance = null;

        if ($sale->customer) {
            $customerBalance = $customerReceivableService->currentBalance($sale->customer);
        }

        return view('sales.show', compact('sale', 'customerBalance'));
    }

    public function edit(Sale $sale): View
    {
        $this->ensureBranchAccess($sale);
        $this->authorize('update', $sale);
        abort_unless($sale->status === 'draft', 403);
        $user = request()->user();

        $customers = Customer::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $sessions = RegisterSession::query()
            ->with('register')
            ->where('status', 'Open')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
            )
            ->latest('opened_at')
            ->get();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereIn('branch_id', $user->branches()
                    ->pluck('branches.id')
                );
            }
            )
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 'Active')
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();

        $variants = ProductVariant::query()
            ->where('status', 'Active')
            ->with('product')
            ->orderBy('name')
            ->get();

        $sale->load('items');

        return view('sales.edit', compact('sale', 'customers', 'sessions', 'warehouses', 'products', 'variants'));
    }

    public function update(UpdateSaleRequest $request, Sale $sale): RedirectResponse
    {
        $this->ensureBranchAccess($sale);

        try {
            $this->saleService->updateDraft($request->user(), $sale, $request->validated());

            return redirect()->route('sales.index', $sale)->with('success', 'Sale updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to update sale.');
        }
    }

    public function cancel(Sale $sale): RedirectResponse
    {
        $this->ensureBranchAccess($sale);
        $this->authorize('cancel', $sale);

        try {
            $this->saleService->cancel($sale);

            return back()->with('success', 'Sale cancelled successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to cancel sale.');
        }
    }

    public function credit(Sale $sale, SaleCompletionService $saleCompletionService): RedirectResponse
    {
        $this->ensureBranchAccess($sale);
        $this->authorize('credit', $sale);

        try {
            $saleCompletionService->completeOnCredit($sale, Auth::user());

            return redirect()->route('sales.show', $sale)->with('success', 'Credit sale completed successfully.');
        } catch (\Throwable $e) {
            Log::error('Credit sale completion failed', [
                'sale_id' => $sale->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
