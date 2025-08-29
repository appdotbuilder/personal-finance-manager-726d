import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Account {
    id: number;
    name: string;
    type: string;
    balance: number;
    currency: string;
    is_active: boolean;
}

interface Transaction {
    id: number;
    type: 'income' | 'expense' | 'transfer';
    amount: number;
    description: string;
    date: string;
    account: Account;
    to_account?: Account;
    category?: {
        id: number;
        name: string;
        color: string;
    };
}

interface ExpenseCategory {
    name: string;
    value: number;
    color: string;
}

interface Debt {
    id: number;
    type: 'debt' | 'receivable';
    person_name: string;
    amount: number;
    paid_amount: number;
    remaining_amount: number;
    due_date?: string;
}

interface SavingsGoal {
    id: number;
    name: string;
    target_amount: number;
    current_amount: number;
    progress_percentage: number;
    target_date?: string;
    account: Account;
}

interface Investment {
    id: number;
    name: string;
    type: string;
    initial_value: number;
    current_value: number;
    gain_loss: number;
    gain_loss_percentage: number;
}

interface Props {
    accounts: Account[];
    totalBalance: number;
    recentTransactions: Transaction[];
    expenseCategories: ExpenseCategory[];
    debts: Debt[];
    savingsGoals: SavingsGoal[];
    investments: {
        total_value: number;
        total_cost: number;
        gain_loss: number;
        items: Investment[];
    };
    monthlyStats: {
        income: number;
        expenses: number;
        net: number;
    };
    [key: string]: unknown;
}

const AccountTypeIcon = ({ type }: { type: string }) => {
    const icons = {
        bank: '🏦',
        e_wallet: '📱',
        cash: '💵',
        credit_card: '💳',
        investment: '📈'
    };
    return <span className="text-lg">{icons[type as keyof typeof icons] || '💰'}</span>;
};

const TransactionTypeIcon = ({ type }: { type: string }) => {
    const icons = {
        income: '💰',
        expense: '💸',
        transfer: '🔄'
    };
    return <span className="text-sm">{icons[type as keyof typeof icons]}</span>;
};

export default function Dashboard({
    accounts,
    totalBalance,
    recentTransactions,
    expenseCategories,
    debts,
    savingsGoals,
    investments,
    monthlyStats
}: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    };

    return (
        <AppShell>
            <Head title="Dashboard" />
            
            <div className="space-y-6">
                {/* Header */}
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">💰 Financial Dashboard</h1>
                    <p className="text-gray-600">Overview of your financial health</p>
                </div>

                {/* Key Metrics */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Total Balance</p>
                                <p className="text-2xl font-bold text-green-600">
                                    {formatCurrency(totalBalance)}
                                </p>
                            </div>
                            <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">💰</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Monthly Income</p>
                                <p className="text-2xl font-bold text-blue-600">
                                    {formatCurrency(monthlyStats.income)}
                                </p>
                            </div>
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">📈</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Monthly Expenses</p>
                                <p className="text-2xl font-bold text-red-600">
                                    {formatCurrency(monthlyStats.expenses)}
                                </p>
                            </div>
                            <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">💸</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Net Income</p>
                                <p className={`text-2xl font-bold ${monthlyStats.net >= 0 ? 'text-green-600' : 'text-red-600'}`}>
                                    {formatCurrency(monthlyStats.net)}
                                </p>
                            </div>
                            <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">📊</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Accounts Overview */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">🏦 Accounts</h2>
                        </div>
                        <div className="p-6">
                            {accounts.length === 0 ? (
                                <p className="text-gray-500 text-center py-4">No accounts yet</p>
                            ) : (
                                <div className="space-y-3">
                                    {accounts.slice(0, 5).map((account) => (
                                        <div key={account.id} className="flex items-center justify-between">
                                            <div className="flex items-center space-x-3">
                                                <AccountTypeIcon type={account.type} />
                                                <div>
                                                    <p className="font-medium text-gray-900">{account.name}</p>
                                                    <p className="text-sm text-gray-500 capitalize">{account.type.replace('_', ' ')}</p>
                                                </div>
                                            </div>
                                            <p className="font-semibold text-gray-900">
                                                {formatCurrency(account.balance)}
                                            </p>
                                        </div>
                                    ))}
                                    {accounts.length > 5 && (
                                        <p className="text-sm text-gray-500 text-center pt-2">
                                            +{accounts.length - 5} more accounts
                                        </p>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Recent Transactions */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">💳 Recent Transactions</h2>
                        </div>
                        <div className="p-6">
                            {recentTransactions.length === 0 ? (
                                <p className="text-gray-500 text-center py-4">No transactions yet</p>
                            ) : (
                                <div className="space-y-3">
                                    {recentTransactions.slice(0, 5).map((transaction) => (
                                        <div key={transaction.id} className="flex items-center justify-between">
                                            <div className="flex items-center space-x-3">
                                                <TransactionTypeIcon type={transaction.type} />
                                                <div>
                                                    <p className="font-medium text-gray-900 truncate max-w-32">
                                                        {transaction.description}
                                                    </p>
                                                    <p className="text-sm text-gray-500">
                                                        {formatDate(transaction.date)}
                                                    </p>
                                                </div>
                                            </div>
                                            <div className="text-right">
                                                <p className={`font-semibold ${
                                                    transaction.type === 'income' ? 'text-green-600' : 
                                                    transaction.type === 'expense' ? 'text-red-600' : 
                                                    'text-gray-900'
                                                }`}>
                                                    {transaction.type === 'income' ? '+' : 
                                                     transaction.type === 'expense' ? '-' : ''}
                                                    {formatCurrency(transaction.amount)}
                                                </p>
                                                <p className="text-xs text-gray-500">{transaction.account.name}</p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Expense Categories */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">📊 Expenses (30 days)</h2>
                        </div>
                        <div className="p-6">
                            {expenseCategories.length === 0 ? (
                                <p className="text-gray-500 text-center py-4">No expenses this month</p>
                            ) : (
                                <div className="space-y-3">
                                    {expenseCategories.slice(0, 5).map((category, index) => (
                                        <div key={index} className="flex items-center justify-between">
                                            <div className="flex items-center space-x-3">
                                                <div 
                                                    className="w-4 h-4 rounded-full"
                                                    style={{ backgroundColor: category.color }}
                                                />
                                                <p className="font-medium text-gray-900">{category.name}</p>
                                            </div>
                                            <p className="font-semibold text-red-600">
                                                {formatCurrency(category.value)}
                                            </p>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Second Row */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Debts & Receivables */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">📋 Outstanding Debts & Receivables</h2>
                        </div>
                        <div className="p-6">
                            {debts.length === 0 ? (
                                <p className="text-gray-500 text-center py-4">No outstanding debts or receivables</p>
                            ) : (
                                <div className="space-y-3">
                                    {debts.map((debt) => (
                                        <div key={debt.id} className="flex items-center justify-between">
                                            <div className="flex items-center space-x-3">
                                                <span className="text-lg">{debt.type === 'debt' ? '📤' : '📥'}</span>
                                                <div>
                                                    <p className="font-medium text-gray-900">{debt.person_name}</p>
                                                    <p className="text-sm text-gray-500">
                                                        {debt.type === 'debt' ? 'You owe' : 'Owes you'}
                                                        {debt.due_date && ` • Due ${formatDate(debt.due_date)}`}
                                                    </p>
                                                </div>
                                            </div>
                                            <div className="text-right">
                                                <p className={`font-semibold ${debt.type === 'debt' ? 'text-red-600' : 'text-green-600'}`}>
                                                    {formatCurrency(debt.remaining_amount)}
                                                </p>
                                                <p className="text-xs text-gray-500">
                                                    of {formatCurrency(debt.amount)}
                                                </p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Savings Goals */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">🎯 Savings Goals</h2>
                        </div>
                        <div className="p-6">
                            {savingsGoals.length === 0 ? (
                                <p className="text-gray-500 text-center py-4">No savings goals set</p>
                            ) : (
                                <div className="space-y-4">
                                    {savingsGoals.map((goal) => (
                                        <div key={goal.id}>
                                            <div className="flex items-center justify-between mb-2">
                                                <p className="font-medium text-gray-900">{goal.name}</p>
                                                <p className="text-sm font-semibold text-gray-900">
                                                    {Math.round(goal.progress_percentage)}%
                                                </p>
                                            </div>
                                            <div className="w-full bg-gray-200 rounded-full h-2">
                                                <div 
                                                    className="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                    style={{ width: `${Math.min(goal.progress_percentage, 100)}%` }}
                                                />
                                            </div>
                                            <div className="flex justify-between text-sm text-gray-500 mt-1">
                                                <span>{formatCurrency(goal.current_amount)}</span>
                                                <span>{formatCurrency(goal.target_amount)}</span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Investment Summary */}
                {investments.items.length > 0 && (
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">📈 Investment Portfolio</h2>
                        </div>
                        <div className="p-6">
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div className="text-center">
                                    <p className="text-sm text-gray-600">Total Value</p>
                                    <p className="text-2xl font-bold text-blue-600">
                                        {formatCurrency(investments.total_value)}
                                    </p>
                                </div>
                                <div className="text-center">
                                    <p className="text-sm text-gray-600">Total Cost</p>
                                    <p className="text-2xl font-bold text-gray-900">
                                        {formatCurrency(investments.total_cost)}
                                    </p>
                                </div>
                                <div className="text-center">
                                    <p className="text-sm text-gray-600">Gain/Loss</p>
                                    <p className={`text-2xl font-bold ${investments.gain_loss >= 0 ? 'text-green-600' : 'text-red-600'}`}>
                                        {investments.gain_loss >= 0 ? '+' : ''}{formatCurrency(investments.gain_loss)}
                                    </p>
                                </div>
                            </div>
                            
                            <div className="space-y-3">
                                {investments.items.slice(0, 3).map((investment) => (
                                    <div key={investment.id} className="flex items-center justify-between">
                                        <div>
                                            <p className="font-medium text-gray-900">{investment.name}</p>
                                            <p className="text-sm text-gray-500 capitalize">{investment.type.replace('_', ' ')}</p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-semibold text-gray-900">
                                                {formatCurrency(investment.current_value)}
                                            </p>
                                            <p className={`text-sm ${investment.gain_loss >= 0 ? 'text-green-600' : 'text-red-600'}`}>
                                                {investment.gain_loss >= 0 ? '+' : ''}{investment.gain_loss_percentage.toFixed(1)}%
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}