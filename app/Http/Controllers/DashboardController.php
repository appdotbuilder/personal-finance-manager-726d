<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Investment;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get accounts with balances
        $accounts = Account::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $totalBalance = $accounts->sum('balance');
        
        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with(['account', 'category', 'toAccount'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get expense data for pie chart (last 30 days)
        $expenseCategories = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('date', '>=', now()->subDays(30))
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($transactions, $categoryId) {
                $category = $transactions->first()->category;
                return [
                    'name' => $category ? $category->name : 'Uncategorized',
                    'value' => $transactions->sum('amount'),
                    'color' => $category ? $category->color : '#6b7280'
                ];
            })
            ->values();
        
        // Get outstanding debts and receivables
        $debts = Debt::where('user_id', $user->id)
            ->where('is_paid', false)
            ->orderBy('due_date')
            ->limit(5)
            ->get();
            
        // Get active savings goals
        $savingsGoals = SavingsGoal::where('user_id', $user->id)
            ->where('is_completed', false)
            ->with('account')
            ->orderBy('target_date')
            ->limit(5)
            ->get();
        
        // Get investment summary
        $investments = Investment::where('user_id', $user->id)->get();
        $totalInvestmentValue = $investments->sum('current_value');
        $totalInvestmentCost = $investments->sum('initial_value');
        $investmentGainLoss = $totalInvestmentValue - $totalInvestmentCost;
        
        // Monthly income vs expenses
        $thisMonth = now()->startOfMonth();
        $monthlyIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->where('date', '>=', $thisMonth)
            ->sum('amount');
            
        $monthlyExpenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('date', '>=', $thisMonth)
            ->sum('amount');
        
        return Inertia::render('dashboard', [
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
            'recentTransactions' => $recentTransactions,
            'expenseCategories' => $expenseCategories,
            'debts' => $debts,
            'savingsGoals' => $savingsGoals,
            'investments' => [
                'total_value' => $totalInvestmentValue,
                'total_cost' => $totalInvestmentCost,
                'gain_loss' => $investmentGainLoss,
                'items' => $investments->take(5)
            ],
            'monthlyStats' => [
                'income' => $monthlyIncome,
                'expenses' => $monthlyExpenses,
                'net' => $monthlyIncome - $monthlyExpenses
            ]
        ]);
    }
}