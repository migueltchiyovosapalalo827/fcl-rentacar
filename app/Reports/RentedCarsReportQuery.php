<?php

namespace App\Reports;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class RentedCarsReportQuery
{
    /**
     * @param  array<string, mixed>|null  $filters
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function period(?array $filters): array
    {
        $from = Carbon::parse($filters['from'] ?? now()->startOfMonth())->startOfDay();
        $until = Carbon::parse($filters['until'] ?? now())->endOfDay();

        if ($from->greaterThan($until)) {
            [$from, $until] = [$until->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $until];
    }

    public static function overlappingRentalsQuery(Carbon $from, Carbon $until): Builder
    {
        return Reservation::query()
            ->with(['car', 'client'])
            ->whereIn('status', ['confirmada', 'ativa', 'concluida'])
            ->where(function (Builder $query) use ($from, $until): void {
                $query->whereBetween('start_date', [$from, $until])
                    ->orWhereBetween('end_date', [$from, $until])
                    ->orWhere(function (Builder $inner) use ($from, $until): void {
                        $inner->where('start_date', '<=', $from)
                            ->where('end_date', '>=', $until);
                    });
            });
    }

    public static function currentlyRentedQuery(): Builder
    {
        return Reservation::query()
            ->with(['car', 'client'])
            ->whereIn('status', ['confirmada', 'ativa'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public static function ranking(Collection $rentals): Collection
    {
        return $rentals
            ->groupBy('car_id')
            ->map(function (Collection $items): ?array {
                $car = $items->first()?->car;

                if (! $car) {
                    return null;
                }

                return [
                    'id' => (string) $car->id,
                    'vehicle' => trim(($car->brand ?? '').' '.($car->model ?? '')) ?: '—',
                    'plate' => $car->plate_number ?? '—',
                    'rentals' => $items->count(),
                    'days' => $items->sum(fn (Reservation $reservation): int => max(
                        1,
                        (int) ceil($reservation->start_date->floatDiffInDays($reservation->end_date)),
                    )),
                    'revenue' => (float) $items->sum('total_amount'),
                ];
            })
            ->filter()
            ->sortByDesc('rentals')
            ->values();
    }

    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            'pendente' => 'Pendente',
            'confirmada' => 'Confirmada',
            'ativa' => 'Ativa',
            'concluida' => 'Concluída',
            'cancelada' => 'Cancelada',
            default => $status ?: '—',
        };
    }

    public static function statusColor(?string $status): string
    {
        return match ($status) {
            'pendente' => 'warning',
            'confirmada' => 'info',
            'ativa' => 'success',
            'concluida' => 'gray',
            'cancelada' => 'danger',
            default => 'gray',
        };
    }

    public static function vehicleLabel(?Reservation $reservation): string
    {
        $car = $reservation?->car;

        if (! $car) {
            return '—';
        }

        return trim(($car->brand ?? '').' '.($car->model ?? '')) ?: '—';
    }
}
