import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Account {
    id: number;
    name: string;
    type: string;
}

interface Category {
    id: number;
    name: string;
    type: 'income' | 'expense';
    color: string;
}

interface Transaction {
    id: number;
    type: 'income' | 'expense' | 'transfer';
    amount: number;
    description: string;
    date: string;
    account: Account;
    to_account?: Account;
    category?: Category;
}

interface PaginationLinks {
    url?: string;
    label: string;
    active: boolean;
}

interface PaginatedTransactions {
    data: Transaction[];
    links: PaginationLinks[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    transactions: PaginatedTransactions;
    accounts: Account[];
    categories: Category[];
    filters: {
        type?: string;
        account_id?: string;
        category_id?: string;
        date_from?: string;
        date_to?: string;
    };
    [key: string]: unknown;
}

const TransactionTypeIcon = ({ type }: { type: string }) => {
    const icons = {
        income: '💰',
        expense: '💸',
        transfer: '🔄'
    };
    return <span className="text-lg">{icons[type as keyof typeof icons]}</span>;
};

export default function TransactionsIndex({ transactions, accounts, categories, filters }: Props) {
    const [filterForm, setFilterForm] = useState(filters);

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

    const getAmountColor = (type: string) => {
        switch (type) {
            case 'income':
                return 'text-green-600';
            case 'expense':
                return 'text-red-600';
            case 'transfer':
                return 'text-blue-600';
            default:
                return 'text-gray-900';
        }
    };

    const handleFilter = () => {
        router.get('/transactions', filterForm, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const clearFilters = () => {
        router.get('/transactions');
    };

    return (
        <AppShell>
            <Head title="Transactions" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">💳 Transactions</h1>
                        <p className="text-gray-600">Track your income, expenses, and transfers</p>
                    </div>
                    <Link href="/transactions/create">
                        <Button>Add Transaction</Button>
                    </Link>
                </div>

                {/* Filters */}
                <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        {/* Type Filter */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <Select 
                                value={filterForm.type || 'all'} 
                                onValueChange={(value) => setFilterForm({...filterForm, type: value === 'all' ? '' : value})}
                            >
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Types</SelectItem>
                                    <SelectItem value="income">💰 Income</SelectItem>
                                    <SelectItem value="expense">💸 Expense</SelectItem>
                                    <SelectItem value="transfer">🔄 Transfer</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        {/* Account Filter */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Account</label>
                            <Select 
                                value={filterForm.account_id || 'all'} 
                                onValueChange={(value) => setFilterForm({...filterForm, account_id: value === 'all' ? '' : value})}
                            >
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Accounts</SelectItem>
                                    {accounts.map((account) => (
                                        <SelectItem key={account.id} value={account.id.toString()}>
                                            {account.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        {/* Category Filter */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <Select 
                                value={filterForm.category_id || 'all'} 
                                onValueChange={(value) => setFilterForm({...filterForm, category_id: value === 'all' ? '' : value})}
                            >
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Categories</SelectItem>
                                    {categories.map((category) => (
                                        <SelectItem key={category.id} value={category.id.toString()}>
                                            <div className="flex items-center space-x-2">
                                                <div 
                                                    className="w-3 h-3 rounded-full"
                                                    style={{ backgroundColor: category.color }}
                                                />
                                                <span>{category.name}</span>
                                            </div>
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        {/* Date From */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">From</label>
                            <Input
                                type="date"
                                value={filterForm.date_from || ''}
                                onChange={(e) => setFilterForm({...filterForm, date_from: e.target.value})}
                            />
                        </div>

                        {/* Date To */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">To</label>
                            <Input
                                type="date"
                                value={filterForm.date_to || ''}
                                onChange={(e) => setFilterForm({...filterForm, date_to: e.target.value})}
                            />
                        </div>

                        {/* Filter Actions */}
                        <div className="flex items-end space-x-2">
                            <Button onClick={handleFilter} variant="outline">Filter</Button>
                            <Button onClick={clearFilters} variant="ghost">Clear</Button>
                        </div>
                    </div>
                </div>

                {/* Transactions List */}
                <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                    {transactions.data.length === 0 ? (
                        <div className="p-12 text-center">
                            <div className="text-6xl mb-4">💳</div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">No transactions yet</h3>
                            <p className="text-gray-600 mb-6">
                                Start tracking your finances by adding your first transaction
                            </p>
                            <Link href="/transactions/create">
                                <Button>Add Your First Transaction</Button>
                            </Link>
                        </div>
                    ) : (
                        <>
                            {/* Table Header */}
                            <div className="px-6 py-4 border-b border-gray-200">
                                <div className="grid grid-cols-12 gap-4 text-sm font-medium text-gray-500">
                                    <div className="col-span-1">Type</div>
                                    <div className="col-span-3">Description</div>
                                    <div className="col-span-2">Account</div>
                                    <div className="col-span-2">Category</div>
                                    <div className="col-span-2">Amount</div>
                                    <div className="col-span-1">Date</div>
                                    <div className="col-span-1">Actions</div>
                                </div>
                            </div>

                            {/* Transaction Rows */}
                            <div className="divide-y divide-gray-200">
                                {transactions.data.map((transaction) => (
                                    <div key={transaction.id} className="px-6 py-4 hover:bg-gray-50">
                                        <div className="grid grid-cols-12 gap-4 items-center">
                                            <div className="col-span-1">
                                                <TransactionTypeIcon type={transaction.type} />
                                            </div>
                                            <div className="col-span-3">
                                                <p className="font-medium text-gray-900">{transaction.description}</p>
                                                {transaction.type === 'transfer' && transaction.to_account && (
                                                    <p className="text-sm text-gray-500">
                                                        To: {transaction.to_account.name}
                                                    </p>
                                                )}
                                            </div>
                                            <div className="col-span-2">
                                                <p className="text-sm text-gray-900">{transaction.account.name}</p>
                                                <p className="text-xs text-gray-500 capitalize">
                                                    {transaction.account.type.replace('_', ' ')}
                                                </p>
                                            </div>
                                            <div className="col-span-2">
                                                {transaction.category ? (
                                                    <div className="flex items-center space-x-2">
                                                        <div 
                                                            className="w-3 h-3 rounded-full"
                                                            style={{ backgroundColor: transaction.category.color }}
                                                        />
                                                        <span className="text-sm text-gray-900">
                                                            {transaction.category.name}
                                                        </span>
                                                    </div>
                                                ) : (
                                                    <span className="text-sm text-gray-400">-</span>
                                                )}
                                            </div>
                                            <div className="col-span-2">
                                                <p className={`font-semibold ${getAmountColor(transaction.type)}`}>
                                                    {transaction.type === 'income' ? '+' : 
                                                     transaction.type === 'expense' ? '-' : ''}
                                                    {formatCurrency(transaction.amount)}
                                                </p>
                                            </div>
                                            <div className="col-span-1">
                                                <p className="text-sm text-gray-900">{formatDate(transaction.date)}</p>
                                            </div>
                                            <div className="col-span-1">
                                                <div className="flex space-x-2">
                                                    <Link href={`/transactions/${transaction.id}`}>
                                                        <Button variant="ghost" size="sm">View</Button>
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Pagination */}
                            {transactions.last_page > 1 && (
                                <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                                    <div className="text-sm text-gray-500">
                                        Showing {transactions.data.length} of {transactions.total} transactions
                                    </div>
                                    <div className="flex space-x-2">
                                        {transactions.links.map((link, index) => (
                                            <button
                                                key={index}
                                                onClick={() => link.url && router.get(link.url)}
                                                disabled={!link.url}
                                                className={`px-3 py-2 text-sm rounded-md ${
                                                    link.active
                                                        ? 'bg-blue-600 text-white'
                                                        : link.url
                                                        ? 'text-gray-700 hover:text-gray-900'
                                                        : 'text-gray-400 cursor-not-allowed'
                                                }`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        ))}
                                    </div>
                                </div>
                            )}
                        </>
                    )}
                </div>

                {/* Summary Stats */}
                {transactions.data.length > 0 && (
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600">Total Income</p>
                                    <p className="text-2xl font-bold text-green-600">
                                        {formatCurrency(
                                            transactions.data
                                                .filter(t => t.type === 'income')
                                                .reduce((sum, t) => sum + t.amount, 0)
                                        )}
                                    </p>
                                </div>
                                <div className="text-2xl">💰</div>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600">Total Expenses</p>
                                    <p className="text-2xl font-bold text-red-600">
                                        {formatCurrency(
                                            transactions.data
                                                .filter(t => t.type === 'expense')
                                                .reduce((sum, t) => sum + t.amount, 0)
                                        )}
                                    </p>
                                </div>
                                <div className="text-2xl">💸</div>
                            </div>
                        </div>

                        <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600">Net Amount</p>
                                    <p className={`text-2xl font-bold ${
                                        (transactions.data.filter(t => t.type === 'income').reduce((sum, t) => sum + t.amount, 0) -
                                         transactions.data.filter(t => t.type === 'expense').reduce((sum, t) => sum + t.amount, 0)) >= 0
                                            ? 'text-green-600' : 'text-red-600'
                                    }`}>
                                        {formatCurrency(
                                            transactions.data.filter(t => t.type === 'income').reduce((sum, t) => sum + t.amount, 0) -
                                            transactions.data.filter(t => t.type === 'expense').reduce((sum, t) => sum + t.amount, 0)
                                        )}
                                    </p>
                                </div>
                                <div className="text-2xl">📊</div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}