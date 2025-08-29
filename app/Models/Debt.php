<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Debt
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $person_name
 * @property float $amount
 * @property float $paid_amount
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property bool $is_paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read float $remaining_amount
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Debt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt wherePersonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt unpaid()
 * @method static \Database\Factories\DebtFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Debt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'person_name',
        'amount',
        'paid_amount',
        'description',
        'due_date',
        'is_paid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'is_paid' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the debt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the remaining amount to be paid.
     *
     * @return float
     */
    public function getRemainingAmountAttribute(): float
    {
        return $this->amount - $this->paid_amount;
    }

    /**
     * Scope a query to only include unpaid debts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }
}