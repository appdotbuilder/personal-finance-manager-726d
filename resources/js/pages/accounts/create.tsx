import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

export default function CreateAccount() {
    const form = useForm({
        name: '',
        type: 'bank',
        balance: '0.00',
        currency: 'USD',
        description: '',
        is_active: true as boolean
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post('/accounts');
    };

    const accountTypes = [
        { value: 'bank', label: '🏦 Bank Account', description: 'Checking, savings, or other bank accounts' },
        { value: 'e_wallet', label: '📱 E-Wallet', description: 'PayPal, Venmo, digital wallets' },
        { value: 'cash', label: '💵 Cash', description: 'Physical cash on hand' },
        { value: 'credit_card', label: '💳 Credit Card', description: 'Credit cards and lines of credit' },
        { value: 'investment', label: '📈 Investment Account', description: 'Brokerage, retirement accounts' }
    ];

    return (
        <AppShell>
            <Head title="Add Account" />
            
            <div className="space-y-6">
                {/* Header */}
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">➕ Add New Account</h1>
                    <p className="text-gray-600">Create a new financial account to track</p>
                </div>

                {/* Form */}
                <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                    <form onSubmit={handleSubmit} className="p-6 space-y-6">
                        {/* Account Name */}
                        <div>
                            <Label htmlFor="name">Account Name *</Label>
                            <Input
                                id="name"
                                type="text"
                                value={form.data.name}
                                onChange={(e) => form.setData('name', e.target.value)}
                                placeholder="e.g., Chase Checking, PayPal, Cash Wallet"
                                className="mt-1"
                            />
                            {form.errors.name && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.name}</p>
                            )}
                        </div>

                        {/* Account Type */}
                        <div>
                            <Label htmlFor="type">Account Type *</Label>
                            <div className="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                {accountTypes.map((type) => (
                                    <label
                                        key={type.value}
                                        className={`relative flex cursor-pointer rounded-lg border p-4 hover:border-blue-300 ${
                                            form.data.type === type.value
                                                ? 'border-blue-500 bg-blue-50'
                                                : 'border-gray-200'
                                        }`}
                                    >
                                        <input
                                            type="radio"
                                            name="type"
                                            value={type.value}
                                            checked={form.data.type === type.value}
                                            onChange={(e) => form.setData('type', e.target.value)}
                                            className="sr-only"
                                        />
                                        <div className="flex flex-1">
                                            <div className="flex flex-col">
                                                <span className="block text-sm font-medium text-gray-900">
                                                    {type.label}
                                                </span>
                                                <span className="mt-1 block text-sm text-gray-500">
                                                    {type.description}
                                                </span>
                                            </div>
                                        </div>
                                        {form.data.type === type.value && (
                                            <div className="absolute -inset-px rounded-lg border-2 border-blue-500 pointer-events-none" />
                                        )}
                                    </label>
                                ))}
                            </div>
                            {form.errors.type && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.type}</p>
                            )}
                        </div>

                        {/* Initial Balance */}
                        <div>
                            <Label htmlFor="balance">Initial Balance *</Label>
                            <div className="mt-1 relative rounded-md shadow-sm">
                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span className="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <Input
                                    id="balance"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value={form.data.balance}
                                    onChange={(e) => form.setData('balance', e.target.value)}
                                    className="pl-7"
                                    placeholder="0.00"
                                />
                            </div>
                            {form.errors.balance && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.balance}</p>
                            )}
                            <p className="mt-1 text-sm text-gray-500">
                                Enter the current balance of this account
                            </p>
                        </div>

                        {/* Currency */}
                        <div>
                            <Label htmlFor="currency">Currency *</Label>
                            <select
                                id="currency"
                                value={form.data.currency}
                                onChange={(e) => form.setData('currency', e.target.value)}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="CAD">CAD - Canadian Dollar</option>
                                <option value="AUD">AUD - Australian Dollar</option>
                            </select>
                            {form.errors.currency && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.currency}</p>
                            )}
                        </div>

                        {/* Description */}
                        <div>
                            <Label htmlFor="description">Description</Label>
                            <Textarea
                                id="description"
                                value={form.data.description}
                                onChange={(e) => form.setData('description', e.target.value)}
                                placeholder="Optional notes about this account"
                                className="mt-1"
                                rows={3}
                            />
                            {form.errors.description && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.description}</p>
                            )}
                        </div>

                        {/* Active Status */}
                        <div className="flex items-center space-x-2">
                            <input
                                id="is_active"
                                type="checkbox"
                                checked={form.data.is_active}
                                onChange={(e) => form.setData('is_active', e.target.checked)}
                                className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <Label htmlFor="is_active" className="text-sm">
                                Account is active
                            </Label>
                        </div>

                        {/* Form Actions */}
                        <div className="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <Button type="button" variant="outline" onClick={() => window.history.back()}>
                                Cancel
                            </Button>
                            <Button type="submit" disabled={form.processing}>
                                {form.processing ? 'Creating...' : 'Create Account'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppShell>
    );
}