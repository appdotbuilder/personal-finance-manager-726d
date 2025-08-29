<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Investment
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $type
 * @property float $initial_value
 * @property float $current_value
 * @property \Illuminate\Support\Carbon $purchase_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read float $gain_loss
 * @property-read float $gain_loss_percentage
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Investment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Investment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Investment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereInitialValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereCurrentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Investment whereUpdatedAt($value)
 * @method static \Database\Factories\InvestmentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Investment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'initial_value',
        'current_value',
        'purchase_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'initial_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'purchase_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the investment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the gain or loss amount.
     *
     * @return float
     */
    public function getGainLossAttribute(): float
    {
        return $this->current_value - $this->initial_value;
    }

    /**
     * Get the gain or loss percentage.
     *
     * @return float
     */
    public function getGainLossPercentageAttribute(): float
    {
        if ($this->initial_value <= 0) {
            return 0;
        }

        return (($this->current_value - $this->initial_value) / $this->initial_value) * 100;
    }
}