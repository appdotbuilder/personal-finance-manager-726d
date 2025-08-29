<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RecurringTransaction
 *
 * @property int $id
 * @property int $user_id
 * @property int $account_id
 * @property int|null $category_id
 * @property string $type
 * @property float $amount
 * @property string $description
 * @property string $frequency
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon $next_due_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Category|null $category
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereNextDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction active()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringTransaction due()
 * @method static \Database\Factories\RecurringTransactionFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class RecurringTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'type',
        'amount',
        'description',
        'frequency',
        'start_date',
        'end_date',
        'next_due_date',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_due_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the recurring transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account that the recurring transaction belongs to.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the category that the recurring transaction belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include active recurring transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include due recurring transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue($query)
    {
        return $query->where('next_due_date', '<=', now()->toDateString());
    }
}