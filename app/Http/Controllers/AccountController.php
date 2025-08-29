<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts.
     */
    public function index(Request $request)
    {
        $accounts = Account::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();
            
        return Inertia::render('accounts/index', [
            'accounts' => $accounts,
            'totalBalance' => $accounts->where('is_active', true)->sum('balance')
        ]);
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        return Inertia::render('accounts/create');
    }

    /**
     * Store a newly created account.
     */
    public function store(StoreAccountRequest $request)
    {
        $account = Account::create([
            'user_id' => $request->user()->id,
            ...$request->validated()
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified account.
     */
    public function show(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            abort(404);
        }
        
        $transactions = $account->transactions()
            ->with(['category', 'toAccount'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return Inertia::render('accounts/show', [
            'account' => $account,
            'transactions' => $transactions
        ]);
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            abort(404);
        }
        
        return Inertia::render('accounts/edit', [
            'account' => $account
        ]);
    }

    /**
     * Update the specified account.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            abort(404);
        }
        
        $account->update($request->validated());

        return redirect()->route('accounts.show', $account)
            ->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified account.
     */
    public function destroy(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            abort(404);
        }
        
        // Check if account has transactions
        if ($account->transactions()->count() > 0) {
            return back()->with('error', 'Cannot delete account with existing transactions.');
        }
        
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}