<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SavingsGoal
 *
 * @property int $id
 * @property int $user_id
 * @property int $account_id
 * @property string $name
 * @property float $target_amount
 * @property float $current_amount
 * @property \Illuminate\Support\Carbon|null $target_date
 * @property string|null $description
 * @property bool $is_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Account $account
 * @property-read float $progress_percentage
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal query()
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereTargetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereCurrentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereTargetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavingsGoal active()
 * @method static \Database\Factories\SavingsGoalFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class SavingsGoal extends Model
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
        'name',
        'target_amount',
        'current_amount',
        'target_date',
        'description',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
        'is_completed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the savings goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account associated with the savings goal.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the progress percentage.
     *
     * @return float
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(($this->current_amount / $this->target_amount) * 100, 100);
    }

    /**
     * Scope a query to only include active savings goals.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }
}