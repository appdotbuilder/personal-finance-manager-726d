import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Account {
    id: number;
    name: string;
    type: string;
    balance: number;
    currency: string;
    is_active: boolean;
    description?: string;
}

interface Props {
    accounts: Account[];
    totalBalance: number;
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
    return <span className="text-xl">{icons[type as keyof typeof icons] || '💰'}</span>;
};

export default function AccountsIndex({ accounts, totalBalance }: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    };

    const getAccountTypeLabel = (type: string) => {
        const labels = {
            bank: 'Bank Account',
            e_wallet: 'E-Wallet',
            cash: 'Cash',
            credit_card: 'Credit Card',
            investment: 'Investment Account'
        };
        return labels[type as keyof typeof labels] || type;
    };

    const getBalanceColor = (balance: number, type: string) => {
        if (type === 'credit_card') {
            return balance < 0 ? 'text-red-600' : 'text-green-600';
        }
        return balance >= 0 ? 'text-green-600' : 'text-red-600';
    };

    return (
        <AppShell>
            <Head title="Accounts" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">🏦 Accounts</h1>
                        <p className="text-gray-600">Manage your financial accounts</p>
                    </div>
                    <Link href="/accounts/create">
                        <Button>Add Account</Button>
                    </Link>
                </div>

                {/* Total Balance Card */}
                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-blue-100 text-sm font-medium">Total Balance</p>
                            <p className="text-3xl font-bold">{formatCurrency(totalBalance)}</p>
                            <p className="text-blue-100 text-sm mt-1">
                                Across {accounts.filter(a => a.is_active).length} active accounts
                            </p>
                        </div>
                        <div className="text-4xl opacity-80">💰</div>
                    </div>
                </div>

                {/* Accounts Grid */}
                {accounts.length === 0 ? (
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <div className="text-6xl mb-4">🏦</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">No accounts yet</h3>
                        <p className="text-gray-600 mb-6">
                            Get started by adding your first financial account
                        </p>
                        <Link href="/accounts/create">
                            <Button>Add Your First Account</Button>
                        </Link>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {accounts.map((account) => (
                            <div key={account.id} className="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <div className="p-6">
                                    <div className="flex items-center justify-between mb-4">
                                        <div className="flex items-center space-x-3">
                                            <div className="p-2 bg-gray-100 rounded-lg">
                                                <AccountTypeIcon type={account.type} />
                                            </div>
                                            <div>
                                                <h3 className="font-semibold text-gray-900">{account.name}</h3>
                                                <p className="text-sm text-gray-500">{getAccountTypeLabel(account.type)}</p>
                                            </div>
                                        </div>
                                        {!account.is_active && (
                                            <span className="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                Inactive
                                            </span>
                                        )}
                                    </div>
                                    
                                    <div className="mb-4">
                                        <p className="text-sm text-gray-600 mb-1">Balance</p>
                                        <p className={`text-2xl font-bold ${getBalanceColor(account.balance, account.type)}`}>
                                            {formatCurrency(account.balance)}
                                        </p>
                                    </div>

                                    {account.description && (
                                        <p className="text-sm text-gray-600 mb-4 truncate">{account.description}</p>
                                    )}

                                    <div className="flex space-x-2">
                                        <Link href={`/accounts/${account.id}`} className="flex-1">
                                            <Button variant="outline" className="w-full">View</Button>
                                        </Link>
                                        <Link href={`/accounts/${account.id}/edit`} className="flex-1">
                                            <Button variant="outline" className="w-full">Edit</Button>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}

                {/* Account Summary */}
                {accounts.length > 0 && (
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Account Summary</h3>
                        <div className="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                            <div>
                                <p className="text-2xl font-bold text-blue-600">
                                    {accounts.filter(a => a.type === 'bank').length}
                                </p>
                                <p className="text-sm text-gray-600">Bank Accounts</p>
                            </div>
                            <div>
                                <p className="text-2xl font-bold text-green-600">
                                    {accounts.filter(a => a.type === 'e_wallet').length}
                                </p>
                                <p className="text-sm text-gray-600">E-Wallets</p>
                            </div>
                            <div>
                                <p className="text-2xl font-bold text-yellow-600">
                                    {accounts.filter(a => a.type === 'cash').length}
                                </p>
                                <p className="text-sm text-gray-600">Cash</p>
                            </div>
                            <div>
                                <p className="text-2xl font-bold text-red-600">
                                    {accounts.filter(a => a.type === 'credit_card').length}
                                </p>
                                <p className="text-sm text-gray-600">Credit Cards</p>
                            </div>
                            <div>
                                <p className="text-2xl font-bold text-purple-600">
                                    {accounts.filter(a => a.type === 'investment').length}
                                </p>
                                <p className="text-sm text-gray-600">Investments</p>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}