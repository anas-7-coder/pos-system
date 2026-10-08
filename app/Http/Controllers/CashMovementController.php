<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashMovementRequest;
use App\Models\CashMovement;
use App\Models\RegisterSession;
use App\Models\User;
use App\Services\CashMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CashMovementController extends Controller
{
    private function ensureSessionBranchAccess(int $registerSessionId): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $session = RegisterSession::query()->findOrFail($registerSessionId);
        abort_unless($user->canAccessBranch((int) $session->branch_id), 403);
    }

    private function accessibleBranchIds(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->accessibleBranchIds();
    }

    public function index(): View
    {
        $this->authorize('viewAny', CashMovement::class);

        /** @var User $user */
        $user = Auth::user();

        $movements = CashMovement::query()
            ->with(['registerSession.register', 'user', 'payment.sale'])
            ->whereHas('registerSession', function ($query) use ($user) {
                if (! $user->hasRole('super_admin')) {
                    $branchIds = $user->branches()
                        ->pluck('branches.id');

                    $query->whereIn('branch_id', $branchIds);
                }
            }
            )
            ->latest('occurred_at')
            ->paginate(20);

        return view('cash-movements.index', compact('movements'));
    }

    public function create(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $registerSessions = RegisterSession::query()
            ->with('register')
            ->where('status', 'Open')
            ->whereIn('branch_id', $user->accessibleBranchIds())
            ->where('user_id', $user->id)
            ->get();
            
        return view('cash-movements.create', compact('registerSessions'));
    }

    public function store(StoreCashMovementRequest $request, CashMovementService $cashMovementService): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $registerSession = RegisterSession::findOrFail($request->input('register_session_id'));
        $this->authorize('create', [CashMovement::class, $registerSession]);

        try {
            $cashMovementService->createManualMovement($registerSession, $request->validated(), $user);

            return redirect()->route('cash-movements.index')->with('success', 'Cash movement recorded successfully.');
        } catch (\Throwable $e) {
            Log::error('Cash movement creation failed', [
                'register_session_id' => $registerSession->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
