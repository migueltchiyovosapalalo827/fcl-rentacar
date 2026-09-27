<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Car extends Model
{
    use HasFactory;

    public const STATUSES = [
        'disponivel' => 'Disponível',
        'alugado' => 'Ocupado',
        'manutencao' => 'Em manutenção',
        'inativo' => 'Inativo',
    ];

    public const CATEGORIES = [
        'economico' => 'Económico',
        'compacto' => 'Compacto',
        'sedan' => 'Sedan',
        'suv' => 'SUV',
        'pickup' => 'Pick-up',
        'luxo' => 'Luxo',
        'van' => 'Van',
    ];

    public const FUEL_TYPES = [
        'gasolina' => 'Gasolina',
        'diesel' => 'Diesel',
        'hibrido' => 'Híbrido',
        'eletrico' => 'Elétrico',
    ];

    public const TRANSMISSIONS = [
        'manual' => 'Manual',
        'automatica' => 'Automática',
    ];

    protected $fillable = [
        'brand',
        'model',
        'plate_number',
        'price_per_day',
        'status',
        'year',
        'km',
        'image',
        'description',
        'color',
        'category',
        'seats',
        'doors',
        'luggage_capacity',
        'fuel_type',
        'transmission',
        'air_conditioning',
        'photos',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'year' => 'integer',
        'km' => 'integer',
        'seats' => 'integer',
        'doors' => 'integer',
        'luggage_capacity' => 'integer',
        'air_conditioning' => 'boolean',
        'photos' => 'array',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenanceReports()
    {
        return $this->hasMany(MaintenanceReport::class);
    }

    public function currentRental(): ?Reservation
    {
        return $this->reservations()
            ->whereIn('status', ['confirmada', 'ativa'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest('start_date')
            ->first();
    }

    public function isOccupied(): bool
    {
        return $this->status === 'alugado' || $this->currentRental() !== null;
    }

    public function isAvailableForRent(): bool
    {
        return $this->status === 'disponivel' && $this->currentRental() === null;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function occupancyLabel(): string
    {
        return $this->isOccupied() ? 'Ocupado' : ($this->status === 'disponivel' ? 'Disponível' : $this->statusLabel());
    }

    public function occupancyColor(): string
    {
        if ($this->isOccupied()) {
            return 'warning';
        }

        return match ($this->status) {
            'disponivel' => 'success',
            'manutencao' => 'danger',
            default => 'gray',
        };
    }

    public function categoryLabel(): ?string
    {
        return $this->category ? (self::CATEGORIES[$this->category] ?? $this->category) : null;
    }

    public function fuelLabel(): ?string
    {
        return $this->fuel_type ? (self::FUEL_TYPES[$this->fuel_type] ?? $this->fuel_type) : null;
    }

    public function transmissionLabel(): ?string
    {
        return $this->transmission ? (self::TRANSMISSIONS[$this->transmission] ?? $this->transmission) : null;
    }

    public function photoUrl(?string $path): ?string
    {
        $path = $this->normalizeStoragePath($path);

        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $public = Storage::disk('public');
        $private = Storage::disk('local');

        if (! $public->exists($path) && $private->exists($path)) {
            $public->put($path, $private->get($path));
        }

        return $public->url($path);
    }

    protected function normalizeStoragePath(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');

        return $path === '' ? null : $path;
    }

    public function coverUrl(): ?string
    {
        return $this->photoUrl($this->image) ?? ($this->galleryUrls()[0] ?? null);
    }

    public function galleryUrls(): array
    {
        return collect([$this->image, ...($this->photos ?? [])])
            ->filter()
            ->unique()
            ->map(fn ($path) => $this->photoUrl($path))
            ->filter()
            ->values()
            ->all();
    }

    public function toPublicArray(bool $withCurrentRental = false): array
    {
        $currentRental = $withCurrentRental ? $this->currentRental() : null;
        $occupied = $this->status === 'alugado' || $currentRental !== null;
        $available = $this->status === 'disponivel' && $currentRental === null;

        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'plate_number' => $this->plate_number,
            'price_per_day' => (float) $this->price_per_day,
            'status' => $this->status,
            'status_label' => $this->statusLabel(),
            'occupancy_label' => $occupied ? 'Ocupado' : ($available ? 'Disponível' : $this->statusLabel()),
            'available' => $available,
            'occupied' => $occupied,
            'year' => $this->year,
            'km' => $this->km,
            'description' => $this->description,
            'color' => $this->color,
            'category' => $this->category,
            'category_label' => $this->categoryLabel(),
            'seats' => $this->seats,
            'doors' => $this->doors,
            'luggage_capacity' => $this->luggage_capacity,
            'fuel_type' => $this->fuel_type,
            'fuel_label' => $this->fuelLabel(),
            'transmission' => $this->transmission,
            'transmission_label' => $this->transmissionLabel(),
            'air_conditioning' => (bool) $this->air_conditioning,
            'cover_url' => $this->coverUrl(),
            'gallery' => $this->galleryUrls(),
            'current_rental_end' => $currentRental?->end_date?->format('d/m/Y H:i'),
        ];
    }
}
