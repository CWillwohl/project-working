<?php

namespace App\Models;

use App\Enums\PunchTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};
use Illuminate\Database\Eloquent\{Builder, Model};

class PunchClock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'punch_time',
        'punch_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function workedPeriod(): HasOne
    {
        if($this->punch_type == 0) {
            return $this->hasOne(WorkingPeriod::class, 'punch_in_id', 'id');
        }

        return $this->hasOne(WorkingPeriod::class, 'punch_out_id', 'id');
    }

    public function getPunchTypeDescriptionAttribute(): string
    {
        return PunchTypeEnum::getDescription($this->punch_type ?? 0);
    }

    public static function searchData(?array $data = []): Builder
    {
        return self::query()
            ->where('user_id', auth()->user()->id)
            ->when($data['dateStart'] ?? null, function ($query, $dateStart) {
                $query->where('punch_time', '>=', Carbon::parse($dateStart)->startOfDay()->format('Y-m-d H:i:s'));
            })
            ->when($data['dateEnd'] ?? null, function ($query, $dateEnd) {
                $query->where('punch_time', '<=', Carbon::parse($dateEnd)->endOfDay()->format('Y-m-d H:i:s'));
            })
            ->when($data['project'] ?? null, function ($query, $project) {
                $query->where('project_id', $project);
            });
    }

    public function getHourAttribute(): int
    {
        return (int)date('H', strtotime($this->punch_time));
    }

    public function getMinuteAttribute(): int
    {
        return (int)date('i', strtotime($this->punch_time));
    }

    public function getSecondAttribute(): int
    {
        return (int)date('s', strtotime($this->punch_time));
    }
}
