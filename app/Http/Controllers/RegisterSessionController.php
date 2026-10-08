<?php

namespace App\Http\Controllers;

use App\Http\Requests\CloseRegisterSessionRequest;
use App\Http\Requests\OpenRegisterSessionRequest;
// use App\Models\Branch;
use App\Models\Register;
use App\Models\RegisterSession;
use App\Services\CashMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RegisterSessionController extends Controller
{
    private function ensureBranchAccess(RegisterSession $registerSession): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->canAccessBranch((int) $registerSession->branch_id), 403);
    }

    public function index(): View
    {
        $this->authorize('viewAny', RegisterSession::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sessions = RegisterSession::query()->with(['register', 'branch', 'user'])->whereIn('branch_id', $user->accessibleBranchIds())->latest()->paginate(15);

        return view('register-sessions.index', compact('sessions'));
    }

    public function create(): View
    {
        $this->authorize('Open', RegisterSession::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $registers = Register::query()
            ->with('branch')
            ->where('status', 'Active')
            ->whereIn('branch_id', $user->accessibleBranchIds())
            ->whereDoesntHave('sessions', function ($query) {
                $query->where('status', 'Open');
            })
            ->orderBy('name')
            ->get();

        return view('register-sessions.create', compact('registers'));
    }

    // public function store(OpenRegisterSessionRequest $request): RedirectResponse
    // {
    //     $this->authorize('Open', RegisterSession::class);
    //     $validated = $request->validated();
    //     $register  = Register::query()->where('status', 'Active')->with('branch')->findOrFail($validated['register_id']);

    //     /*
    //      * The user must have access to the register's branch.
    //      */
    //     abort_unless($request->user()->canAccessBranch($register->branch_id), 403, 'You are not authorized to use this register.');

    //     /*
    //      * Double-check that this register has no open session.
    //      */
    //     $hasOpenSession = $register->sessions()->where('status', 'Open')->exists();

    //     abort_if($hasOpenSession, 422, 'This register already has an open session.');

    //     $session = RegisterSession::create([
    //         'register_id'   => $register->id,
    //         'branch_id'     => $register->branch_id,
    //         'user_id'       => $request->user()->id,
    //         'opened_at'     => now(),
    //         'opening_cash'  => $validated['opening_cash'],
    //         'expected_cash' => $validated['opening_cash'],
    //         'status'        => 'Open',
    //     ]);

    //     return redirect()->route('register-sessions.show', $session)->with('success', 'Register session opened successfully.');
    // }

    public function store(OpenRegisterSessionRequest $request): RedirectResponse
    {
        $this->authorize('Open', RegisterSession::class);
        $validated = $request->validated();
        $session = DB::transaction(function () use ($request, $validated) {

        /** @var \App\Models\User $user */
        $user = Auth::user();

            $register = Register::query()
                ->where('status', 'Active')
                ->with('branch')
                ->lockForUpdate()
                ->whereIn('branch_id', $user->accessibleBranchIds())
                ->findOrFail($validated['register_id']);

            /*
            * The user must have access to the register's branch.
            */
            abort_unless($request->user()->canAccessBranch($register->branch_id), 403, 'You are not authorized to use this register.');

            /*
            * Check again while the register row is locked.
            */
            $hasOpenSession = $register->sessions()
                ->where('status', 'Open')
                ->exists();

            abort_if($hasOpenSession, 422, 'This register already has an open session.');

            return RegisterSession::create([
                'register_id' => $register->id,
                'branch_id' => $register->branch_id,
                'user_id' => $request->user()->id,
                'opened_at' => now(),
                'opening_cash' => $validated['opening_cash'],
                'expected_cash' => $validated['opening_cash'],
                'status' => 'Open',
            ]);
        });

        return redirect()->route('register-sessions.show', $session)->with('success', 'Register session opened successfully.');
    }

    public function show(RegisterSession $registerSession): View
    {
        $this->authorize('view', $registerSession);
        $this->ensureBranchAccess($registerSession);
        $registerSession->load(['register', 'branch', 'user']);

        return view('register-sessions.show', compact('registerSession'));
    }

    // public function close(CloseRegisterSessionRequest $request, RegisterSession $registerSession, CashMovementService $cashMovementService): RedirectResponse
    // {
    //     $this->authorize('close', $registerSession);

    //     abort_if($registerSession->status !== 'Open', 422, 'This register session is already closed.');

    //     $cashMovementService->recalculateExpectedCash($registerSession);

    //     $registerSession->refresh();

    //     $expectedCash = (float) $registerSession->expected_cash;
    //     $actualCash   = (float) $request->validated('actual_cash');
    //     $difference   = round($actualCash - $expectedCash, 2);

    //     $registerSession->update([
    //         'expected_cash' => $expectedCash,
    //         'actual_cash'   => $actualCash,
    //         'difference'    => $difference,
    //         'closed_at'     => now(),
    //         'status'        => 'Closed',
    //     ]);

    //     return redirect()->route('register-sessions.show', $registerSession)->with('success', 'Register session closed successfully.');
    // }

    public function close(CloseRegisterSessionRequest $request, RegisterSession $registerSession, CashMovementService $cashMovementService): RedirectResponse
    {
        $this->authorize('Close', $registerSession);

        DB::transaction(function () use ($request, $registerSession, $cashMovementService) {
            $session = RegisterSession::query()
                ->lockForUpdate()
                ->findOrFail($registerSession->id);

            abort_if($session->status !== 'Open', 422, 'This register session is already closed.');

            /*
            * Recalculate expected cash from all posted cash movements.
            */
            $cashMovementService->recalculateExpectedCash($session);
            $session->refresh();

            $expectedCash = round((float) $session->expected_cash, 2);
            $actualCash = round((float) $request->validated('actual_cash'), 2);
            $difference = round($actualCash - $expectedCash, 2);

            $session->update([
                'expected_cash' => $expectedCash,
                'actual_cash' => $actualCash,
                'difference' => $difference,
                'closed_at' => now(),
                'status' => 'Closed',
            ]);
        });

        return redirect()->route('register-sessions.show', $registerSession)->with('success', 'Register session closed successfully.');
    }
}
