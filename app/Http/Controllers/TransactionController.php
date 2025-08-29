<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', $request->user()->id)
            ->with(['account', 'category', 'toAccount']);
            
        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('account_id') && $request->account_id) {
            $query->where('account_id', $request->account_id);
        }
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }
            
        $transactions = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
            
        $accounts = Account::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $categories = Category::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();
            
        return Inertia::render('transactions/index', [
            'transactions' => $transactions,
            'accounts' => $accounts,
            'categories' => $categories,
            'filters' => $request->only(['type', 'account_id', 'category_id', 'date_from', 'date_to'])
        ]);
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(Request $request)
    {
        $accounts = Account::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $categories = Category::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();
            
        return Inertia::render('transactions/create', [
            'accounts' => $accounts,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(StoreTransactionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            
            $transaction = Transaction::create($data);
            
            // Update account balances
            $this->updateAccountBalances($transaction);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(404);
        }
        
        $transaction->load(['account', 'category', 'toAccount']);
        
        return Inertia::render('transactions/show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(404);
        }
        
        $accounts = Account::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $categories = Category::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();
        
        return Inertia::render('transactions/edit', [
            'transaction' => $transaction,
            'accounts' => $accounts,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified transaction.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(404);
        }
        
        DB::transaction(function () use ($request, $transaction) {
            // Reverse the old transaction's balance changes
            $this->reverseAccountBalances($transaction);
            
            // Update the transaction
            $transaction->update($request->validated());
            
            // Apply the new transaction's balance changes
            $this->updateAccountBalances($transaction->fresh());
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(404);
        }
        
        DB::transaction(function () use ($transaction) {
            // Reverse the transaction's balance changes
            $this->reverseAccountBalances($transaction);
            
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Update account balances based on transaction.
     */
    protected function updateAccountBalances(Transaction $transaction): void
    {
        $account = $transaction->account;
        
        if ($transaction->type === 'income') {
            $account->increment('balance', $transaction->amount);
        } elseif ($transaction->type === 'expense') {
            $account->decrement('balance', $transaction->amount);
        } elseif ($transaction->type === 'transfer' && $transaction->to_account_id) {
            // Deduct from source account
            $account->decrement('balance', $transaction->amount);
            // Add to destination account
            $transaction->toAccount->increment('balance', $transaction->amount);
        }
    }

    /**
     * Reverse account balance changes for a transaction.
     */
    protected function reverseAccountBalances(Transaction $transaction): void
    {
        $account = $transaction->account;
        
        if ($transaction->type === 'income') {
            $account->decrement('balance', $transaction->amount);
        } elseif ($transaction->type === 'expense') {
            $account->increment('balance', $transaction->amount);
        } elseif ($transaction->type === 'transfer' && $transaction->to_account_id) {
            // Add back to source account
            $account->increment('balance', $transaction->amount);
            // Deduct from destination account
            $transaction->toAccount->decrement('balance', $transaction->amount);
        }
    }
}