<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRefundRequest;
use App\Models\Refund;
use App\Models\RegisterSession;
use App\Models\SaleReturn;
use App\Services\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RefundController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Refund::class);

        $refunds = Refund::query()
            ->with(['sale', 'saleReturn', 'registerSession.register', 'createdBy'])
            ->latest('refunded_at')
            ->paginate(20);

        return view('refunds.index', compact('refunds'));
    }

    public function create(SaleReturn $saleReturn): View
    {
        $this->authorize('create', [Refund::class, $saleReturn]);
        $saleReturn->load(['sale', 'refund', 'items.product']);

        $registerSessions = RegisterSession::query()
            ->with('register')
            ->where('status', 'Open')
            ->where('user_id', Auth::id())
            ->where('branch_id', $saleReturn->branch_id)
            ->get();

        return view('refunds.create', compact('saleReturn', 'registerSessions'));
    }

    public function store(StoreRefundRequest $request, SaleReturn $saleReturn, RefundService $refundService): RedirectResponse
    {
        $this->authorize('create', [Refund::class, $saleReturn]);

        try {
            $refundService->create($saleReturn, $request->validated(), Auth::user());

            return redirect()->route('sale-returns.show', $saleReturn)->with('success', 'Refund processed successfully.');
        } catch (\Throwable $e) {
            Log::error('Refund creation failed', [
                'sale_return_id' => $saleReturn->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
