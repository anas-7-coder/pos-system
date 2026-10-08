<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private function ensureBranchAccess(Sale $sale): void
    {   
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->canAccessBranch((int) $sale->branch_id), 403);
    }

    public function index(): View
    {
        $this->authorize('viewAny', Payment::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $payments = Payment::query()
            ->with(['sale', 'createdBy', 'registerSession.register'])
            ->whereHas('sale', function ($query) use ($user) {
                if (! $user->hasRole('super_admin')) {
                    $branchIds = $user->branches()
                        ->pluck('branches.id');

                    $query->whereIn('branch_id', $branchIds);
                }
            })
            ->latest('paid_at')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    public function create(Sale $sale): View
    {
        $this->ensureBranchAccess($sale);
        $this->authorize('create', [Payment::class, $sale]);
        $sale->load(['customer', 'items.product', 'items.productVariant', 'payments' => fn ($query) => $query->where('status', 'posted')->latest('paid_at')]);

        $paidAmount = $sale->payments->sum('amount');
        $remainingAmount = max(0, (float) $sale->grand_total - (float) $paidAmount);

        return view('payments.create', compact('sale', 'paidAmount', 'remainingAmount'));
    }

    public function store(StorePaymentRequest $request, Sale $sale, PaymentService $paymentService): RedirectResponse
    {
        $this->ensureBranchAccess($sale);
        $this->authorize('create', [Payment::class, $sale]);

        /** @var User $user */
        $user = Auth::user();

        try {

            $payment = $paymentService->create($sale, $request->validated(), $user);
            $sale = $payment->sale->fresh();

            if ($sale->status === 'completed') {
                return redirect()->route('sales.show', $sale)->with('success', 'Payment recorded and sale completed successfully.');
            }

            return redirect()->route('sales.show', $sale)->with('success', 'Payment recorded successfully.');

        } catch (\Throwable $e) {
            Log::error('Payment creation failed', [
                'sale_id' => $sale->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->getMessage() : 'Payment could not be recorded.');
        }
    }
}
