<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Builder, Collection, Model};

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'price_per_hour',
        'price_to_receive',
        'total_received',
    ];

    protected $casts = [
        'price_to_receive' => 'float',
        'total_received'   => 'float',
        'excluded_weekdays' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function searchData(?array $data = []): Builder
    {
        return self::query()
                ->where('user_id', auth()->user()->id ?? null)
                ->when($data['search'] ?? null, function ($query, $search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
    }

    public function clockPunchs(): HasMany
    {
        return $this->hasMany(PunchClock::class);
    }

    public function workedPeriods(): HasMany
    {
        return $this->hasMany(WorkingPeriod::class);
    }

    public function getLastSixPunchs(): Collection
    {
        return $this->clockPunchs()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    }

    public function updateReceivedAndPendingValues(array $elements, string $typeModal): bool
    {
        if ($typeModal === 'received') {
            $valueToReceive = WorkingPeriod::getValuesToReceive($elements);
            WorkingPeriod::updateReceivedStatus($elements, received: true);

            $this->price_to_receive = $this->price_to_receive - $valueToReceive;
            $this->total_received += $valueToReceive;
            $this->save();

            return true;
        }

        if($typeModal === 'pending') {
            $receivedValues = WorkingPeriod::getReceivedValues($elements);
            WorkingPeriod::updateReceivedStatus($elements, received: false);

            $this->total_received = max($this->total_received - $receivedValues, 0);
            $this->price_to_receive += $receivedValues;
            $this->save();

            return true;
        }

        return false;
    }

    public function getFormattedPricePerHourAttribute(): string
    {
        return 'R$ ' . $this->formatMoney($this->price_per_hour);
    }

    public function getFormattedTotalReceivedAttribute(): string
    {
        return 'R$ ' . $this->formatMoney($this->total_received);
    }

    public function getFormattedPriceToReceiveAttribute(): string
    {
        return 'R$ ' . number_format(floatval($this->price_to_receive), 2, ',', '.');
    }

    public function formatMoney(mixed $value = '0.00'): string
    {
        return number_format($value, 2, ',', '.');
    }
}
