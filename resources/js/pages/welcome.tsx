import React from 'react';
import { Link } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';

interface SharedData {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
        } | null;
    };
    [key: string]: unknown;
}

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            {/* Navigation */}
            <nav className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <div className="flex items-center space-x-2">
                            <div className="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-sm">💰</span>
                            </div>
                            <h1 className="text-xl font-bold text-gray-900">FinanceTracker</h1>
                        </div>
                        
                        <div className="flex items-center space-x-4">
                            {auth.user ? (
                                <Link
                                    href="/dashboard"
                                    className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href="/login"
                                        className="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md transition-colors"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        Sign up
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
                <div className="text-center">
                    <h1 className="text-5xl font-bold text-gray-900 mb-6">
                        💰 Take Control of Your 
                        <span className="text-blue-600"> Finances</span>
                    </h1>
                    <p className="text-xl text-gray-600 mb-12 max-w-3xl mx-auto">
                        A comprehensive personal finance management platform to track income, expenses, 
                        debts, savings goals, and investments all in one place.
                    </p>
                    
                    {!auth.user && (
                        <div className="flex justify-center space-x-4">
                            <Link
                                href="/register"
                                className="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors shadow-lg"
                            >
                                Get Started Free
                            </Link>
                            <Link
                                href="/login"
                                className="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-colors border-2 border-blue-600"
                            >
                                Log In
                            </Link>
                        </div>
                    )}
                </div>
            </div>

            {/* Features Grid */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h2 className="text-3xl font-bold text-center text-gray-900 mb-12">
                    Everything you need to manage your money
                </h2>
                
                <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {/* Dashboard Overview */}
                    <div className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <span className="text-2xl">📊</span>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Smart Dashboard</h3>
                        <p className="text-gray-600">
                            Get a complete overview of your financial health with real-time balance tracking, 
                            expense charts, and key metrics.
                        </p>
                    </div>

                    {/* Account Management */}
                    <div className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <span className="text-2xl">🏦</span>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Multi-Account Support</h3>
                        <p className="text-gray-600">
                            Manage bank accounts, e-wallets, cash, credit cards, and investment accounts 
                            with automatic balance updates.
                        </p>
                    </div>

                    {/* Transaction Tracking */}
                    <div className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <span className="text-2xl">💳</span>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Smart Transactions</h3>
                        <p className="text-gray-600">
                            Record income, expenses, and transfers with categorization, filtering, 
                            and automatic account balance adjustments.
                        </p>
                    </div>

                    {/* Debt Management */}
                    <div className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                            <span className="text-2xl">📋</span>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Debt & Receivables</h3>
                        <p className="text-gray-600">
                            Track money you owe and money owed to you. Mark as paid to automatically 
                            generate transactions and update balances.
                        </p>
                    </div>

                    {/* Savings Goals */}
                    <div className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                            <span className="text-2xl">🎯</span>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Savings Goals</h3>
                        <p className="text-gray-600">
                            Set and track savings goals with progress visualization. Add or withdraw 
                            funds with automatic account linking.
                        </p>
                    </div>

                    {/* Investment Tracking */}
                    <div className="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <span className="text-2xl">📈</span>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Investment Portfolio</h3>
                        <p className="text-gray-600">
                            Monitor your investments including stocks, bonds, crypto, and real estate 
                            with gain/loss tracking and performance metrics.
                        </p>
                    </div>
                </div>
            </div>

            {/* Dashboard Preview */}
            <div className="bg-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            See your finances at a glance
                        </h2>
                        <p className="text-lg text-gray-600">
                            Beautiful, intuitive dashboard designed for clarity and quick insights
                        </p>
                    </div>
                    
                    {/* Mock Dashboard Preview */}
                    <div className="bg-gray-50 rounded-2xl p-8 shadow-xl">
                        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div className="bg-white p-4 rounded-lg shadow">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm text-gray-600">Total Balance</p>
                                        <p className="text-2xl font-bold text-green-600">$12,485.50</p>
                                    </div>
                                    <div className="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <span className="text-green-600">💰</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="bg-white p-4 rounded-lg shadow">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm text-gray-600">This Month</p>
                                        <p className="text-2xl font-bold text-blue-600">+$2,340</p>
                                    </div>
                                    <div className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span className="text-blue-600">📊</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="bg-white p-4 rounded-lg shadow">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm text-gray-600">Expenses</p>
                                        <p className="text-2xl font-bold text-red-600">$1,842</p>
                                    </div>
                                    <div className="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                        <span className="text-red-600">💸</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="bg-white p-4 rounded-lg shadow">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm text-gray-600">Savings Goals</p>
                                        <p className="text-2xl font-bold text-purple-600">73%</p>
                                    </div>
                                    <div className="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <span className="text-purple-600">🎯</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div className="text-center text-gray-500">
                            <p className="text-sm">✨ Clean, modern interface with real-time updates</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* CTA Section */}
            {!auth.user && (
                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
                    <div className="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                        <h2 className="text-3xl font-bold text-white mb-4">
                            Ready to take control of your finances?
                        </h2>
                        <p className="text-xl text-blue-100 mb-8">
                            Join thousands of users who trust FinanceTracker to manage their money smarter.
                        </p>
                        <Link
                            href="/register"
                            className="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-colors shadow-lg inline-block"
                        >
                            Start Your Financial Journey
                        </Link>
                    </div>
                </div>
            )}

            {/* Footer */}
            <footer className="bg-gray-900 text-white py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                            <div className="w-6 h-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded flex items-center justify-center">
                                <span className="text-white text-xs">💰</span>
                            </div>
                            <span className="font-semibold">FinanceTracker</span>
                        </div>
                        <p className="text-gray-400 text-sm">
                            © 2024 FinanceTracker. Manage your money with confidence.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}