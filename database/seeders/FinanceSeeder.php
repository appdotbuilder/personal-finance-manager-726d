<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Investment;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create a test user
        $user = User::first();
        
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Create accounts
        $accounts = [
            [
                'name' => 'Main Checking',
                'type' => 'bank',
                'balance' => 5420.50,
                'currency' => 'USD',
                'description' => 'Primary checking account',
            ],
            [
                'name' => 'Savings Account',
                'type' => 'bank',
                'balance' => 12480.75,
                'currency' => 'USD',
                'description' => 'High-yield savings account',
            ],
            [
                'name' => 'PayPal',
                'type' => 'e_wallet',
                'balance' => 340.25,
                'currency' => 'USD',
                'description' => 'PayPal account for online transactions',
            ],
            [
                'name' => 'Cash Wallet',
                'type' => 'cash',
                'balance' => 245.00,
                'currency' => 'USD',
                'description' => 'Physical cash on hand',
            ],
            [
                'name' => 'Credit Card',
                'type' => 'credit_card',
                'balance' => -850.30,
                'currency' => 'USD',
                'description' => 'Visa credit card',
            ]
        ];

        foreach ($accounts as $accountData) {
            Account::factory()->create([
                'user_id' => $user->id,
                ...$accountData
            ]);
        }

        // Create categories
        $incomeCategories = [
            ['name' => 'Salary', 'type' => 'income', 'color' => '#10b981'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#3b82f6'],
            ['name' => 'Investment Returns', 'type' => 'income', 'color' => '#8b5cf6'],
            ['name' => 'Side Hustle', 'type' => 'income', 'color' => '#f59e0b'],
        ];

        $expenseCategories = [
            ['name' => 'Food & Dining', 'type' => 'expense', 'color' => '#ef4444'],
            ['name' => 'Transportation', 'type' => 'expense', 'color' => '#f97316'],
            ['name' => 'Entertainment', 'type' => 'expense', 'color' => '#ec4899'],
            ['name' => 'Bills & Utilities', 'type' => 'expense', 'color' => '#6b7280'],
            ['name' => 'Shopping', 'type' => 'expense', 'color' => '#a855f7'],
            ['name' => 'Healthcare', 'type' => 'expense', 'color' => '#06b6d4'],
            ['name' => 'Education', 'type' => 'expense', 'color' => '#84cc16'],
        ];

        foreach (array_merge($incomeCategories, $expenseCategories) as $categoryData) {
            Category::factory()->create([
                'user_id' => $user->id,
                ...$categoryData
            ]);
        }

        // Get created accounts and categories
        $userAccounts = Account::where('user_id', $user->id)->get();
        $userCategories = Category::where('user_id', $user->id)->get();
        $incomeCategs = $userCategories->where('type', 'income');
        $expenseCategs = $userCategories->where('type', 'expense');

        // Create transactions
        for ($i = 0; $i < 50; $i++) {
            $account = $userAccounts->random();
            $type = fake()->randomElement(['income', 'expense', 'transfer']);
            
            $transactionData = [
                'user_id' => $user->id,
                'account_id' => $account->id,
                'type' => $type,
                'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            ];

            if ($type === 'income') {
                $transactionData['category_id'] = $incomeCategs->random()->id;
                $transactionData['amount'] = fake()->randomFloat(2, 100, 3000);
                $transactionData['description'] = fake()->randomElement([
                    'Salary Payment', 'Freelance Project', 'Investment Dividend', 'Bonus Payment', 'Side Project'
                ]);
            } elseif ($type === 'expense') {
                $transactionData['category_id'] = $expenseCategs->random()->id;
                $transactionData['amount'] = fake()->randomFloat(2, 5, 500);
                $transactionData['description'] = fake()->randomElement([
                    'Grocery Shopping', 'Gas Station', 'Restaurant Bill', 'Online Purchase', 
                    'Utility Bill', 'Coffee Shop', 'Movie Tickets', 'Gym Membership'
                ]);
            } else { // transfer
                $toAccount = $userAccounts->where('id', '!=', $account->id)->random();
                $transactionData['to_account_id'] = $toAccount->id;
                $transactionData['amount'] = fake()->randomFloat(2, 50, 1000);
                $transactionData['description'] = 'Transfer between accounts';
            }

            Transaction::create($transactionData);
        }

        // Create debts and receivables
        for ($i = 0; $i < 8; $i++) {
            $amount = fake()->randomFloat(2, 100, 2000);
            $paidAmount = fake()->boolean(60) ? fake()->randomFloat(2, 0, $amount * 0.7) : 0;
            
            Debt::factory()->create([
                'user_id' => $user->id,
                'type' => fake()->randomElement(['debt', 'receivable']),
                'person_name' => fake()->name(),
                'amount' => $amount,
                'paid_amount' => $paidAmount,
                'is_paid' => $paidAmount >= $amount,
                'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
                'description' => fake()->randomElement([
                    'Personal loan', 'Borrowed money', 'Shared expense', 'Business loan', 'Family loan'
                ])
            ]);
        }

        // Create savings goals
        $savingsGoals = [
            [
                'name' => 'Emergency Fund',
                'target_amount' => 10000,
                'current_amount' => 6500,
                'target_date' => '2024-12-31',
                'description' => '6 months of expenses for emergencies'
            ],
            [
                'name' => 'Vacation to Europe',
                'target_amount' => 5000,
                'current_amount' => 2340,
                'target_date' => '2024-08-15',
                'description' => 'Two week vacation in Europe'
            ],
            [
                'name' => 'New Car Down Payment',
                'target_amount' => 8000,
                'current_amount' => 3200,
                'target_date' => '2025-03-01',
                'description' => 'Down payment for new car'
            ]
        ];

        foreach ($savingsGoals as $goalData) {
            SavingsGoal::factory()->create([
                'user_id' => $user->id,
                'account_id' => $userAccounts->where('type', 'bank')->first()->id,
                ...$goalData
            ]);
        }

        // Create investments
        $investments = [
            [
                'name' => 'Apple Inc. (AAPL)',
                'type' => 'stocks',
                'initial_value' => 5000,
                'current_value' => 5850,
                'purchase_date' => '2023-06-15',
                'notes' => '100 shares purchased'
            ],
            [
                'name' => 'S&P 500 ETF',
                'type' => 'mutual_funds',
                'initial_value' => 3000,
                'current_value' => 3420,
                'purchase_date' => '2023-01-10',
                'notes' => 'Diversified market exposure'
            ],
            [
                'name' => 'Bitcoin',
                'type' => 'crypto',
                'initial_value' => 2000,
                'current_value' => 1650,
                'purchase_date' => '2023-11-20',
                'notes' => '0.05 BTC purchased'
            ],
            [
                'name' => 'Government Bonds',
                'type' => 'bonds',
                'initial_value' => 4000,
                'current_value' => 4160,
                'purchase_date' => '2023-03-01',
                'notes' => '10-year treasury bonds'
            ]
        ];

        foreach ($investments as $investmentData) {
            Investment::factory()->create([
                'user_id' => $user->id,
                ...$investmentData
            ]);
        }

        // Update account balances based on transactions
        foreach ($userAccounts as $account) {
            $incomeTotal = Transaction::where('account_id', $account->id)
                ->where('type', 'income')
                ->sum('amount');
                
            $expenseTotal = Transaction::where('account_id', $account->id)
                ->where('type', 'expense')
                ->sum('amount');
                
            $transfersIn = Transaction::where('to_account_id', $account->id)
                ->where('type', 'transfer')
                ->sum('amount');
                
            $transfersOut = Transaction::where('account_id', $account->id)
                ->where('type', 'transfer')
                ->sum('amount');
                
            $calculatedBalance = $account->balance + $incomeTotal - $expenseTotal + $transfersIn - $transfersOut;
            
            // Update balance to reflect transactions
            $account->update(['balance' => $calculatedBalance]);
        }
    }
}