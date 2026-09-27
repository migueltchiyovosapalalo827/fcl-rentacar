<?php

namespace App\Providers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Observers\PaymentObserver;
use App\Observers\ReservationObserver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Reservation::observe(ReservationObserver::class);
        Payment::observe(PaymentObserver::class);

        $this->ensurePublicUploadsAreAvailable();
    }

    private function ensurePublicUploadsAreAvailable(): void
    {
        File::ensureDirectoryExists(storage_path('app/public/cars'));
        File::ensureDirectoryExists(storage_path('app/private/livewire-tmp'));

        $this->migratePrivateUploadsToPublicDisk();
        $this->ensureStorageLink();
    }

    private function migratePrivateUploadsToPublicDisk(): void
    {
        $private = Storage::disk('local');
        $public = Storage::disk('public');

        if (! $private->exists('cars')) {
            return;
        }

        foreach ($private->files('cars') as $file) {
            $normalized = str_replace('\\', '/', $file);

            if (! $public->exists($normalized)) {
                $public->put($normalized, $private->get($file));
            }
        }
    }

    private function ensureStorageLink(): void
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        if (is_link($link) || file_exists($link)) {
            return;
        }

        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $linkPath = str_replace('/', '\\', $link);
                $targetPath = str_replace('/', '\\', $target);
                @exec('cmd /c mklink /J '.escapeshellarg($linkPath).' '.escapeshellarg($targetPath));

                return;
            }

            @symlink($target, $link);
        } catch (\Throwable) {
            // O disco public já serve /storage sem symlink.
        }
    }
}
