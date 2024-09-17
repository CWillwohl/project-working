<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Builder, Model};

class WorkingPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'punch_in_id',
        'punch_out_id',
        'punch_in_time',
        'punch_out_time',
        'value_to_receive',
        'time_worked',
        'received',
        'description',
    ];

    protected $casts = [
        'value_to_receive' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function punchIn(): BelongsTo
    {
        return $this->belongsTo(PunchClock::class, 'punch_in_id');
    }

    public function punchOut(): BelongsTo
    {
        return $this->belongsTo(PunchClock::class, 'punch_out_id');
    }

    public function calculateValueToReceive(string $punchTime, int $punchType): string
    {
        $workedTimeInSeconds = $this->getWorkedTimeInSeconds($punchTime, $punchType);

        $pricePerHour = floatval($this->project->price_per_hour);

        return number_format(($workedTimeInSeconds / 3600) * $pricePerHour, 2, '.', '');
    }

    public function calculateTimeWorked(string $punchTime, int $punchType): string
    {
        if($punchType) {
            return Carbon::parse($this->punchIn->punch_time)->diff(Carbon::parse($punchTime))->format('%H:%I');
        }

        return Carbon::parse($punchTime)->diff(Carbon::parse($this->punch_out_time ?? $punchTime))->format('%H:%I');
    }

    public static function searchData(
        ?int $projectId = null,
        ?array $data = []
    ): Builder {
        $query = self::query()
            ->where('user_id', auth()->user()->id ?? null)
            ->where('project_id', $projectId)
            ->when($data['dateStart'] ?? null, function ($query, $dateStart) {
                $query->where('punch_in_time', '>=', Carbon::parse($dateStart)->startOfDay()->format('Y-m-d H:i:s'));
            })
            ->when($data['dateEnd'] ?? null, function ($query, $dateEnd) {
                $query->where('punch_in_time', '<=', Carbon::parse($dateEnd)->endOfDay()->format('Y-m-d H:i:s'));
            });

        if(isset($data['received'])) {
            $query->where('received', $data['received']);
        }

        return $query;
    }

    public function getWorkedTimeInSeconds(string $punchTime, int $punchType): int
    {
        if($punchType) {
            return Carbon::parse($this->punchIn->punch_time)->diffInSeconds(Carbon::parse($punchTime));
        }

        return Carbon::parse($punchTime)->diffInSeconds(Carbon::parse($this->punch_out_time ?? $punchTime));
    }

    public static function getValuesToReceive(?array $elements = []): float
    {
        $value = 0;
        $data  = self::query()
            ->when($elements, function ($query) use ($elements) {
                $query->whereIn('id', $elements);
            })
            ->where('received', false)
            ->get();

        $data = $data->map(function ($item) use (&$value) {
            return $value += $item->value_to_receive;
        });

        return $value;
    }

    public static function getReceivedValues(?array $elements = []): float
    {
        $value = 0;
        $data  = self::query()
            ->when($elements, function ($query) use ($elements) {
                $query->whereIn('id', $elements);
            })
            ->where('received', true)
            ->get();

        $data = $data->map(function ($item) use (&$value) {
            return $value += $item->value_to_receive;
        });

        return $value;
    }

    public static function updateReceivedStatus(?array $elements = [], bool $received = true): void
    {
        self::query()
            ->when($elements, function ($query) use ($elements) {
                $query->whereIn('id', $elements);
            })
            ->where('received', !$received)
            ->update(['received' => $received]);
    }

    public function getFormattedValueToReceiveAttribute(): string
    {
        return 'R$ ' . number_format(floatval($this->value_to_receive), 2, ',', '.');
    }

    public function getStatusReceivedDescriptionAttribute(): string
    {
        return $this->received ? 'Recebido' : 'Pendente';
    }
}
