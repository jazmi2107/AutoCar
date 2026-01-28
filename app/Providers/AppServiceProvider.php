<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\View\Composers\UserProfileComposer;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Share user with driver relationship across all user views
        View::composer('users.*', UserProfileComposer::class);

        // Optional: run SQL migrations only if explicitly enabled
        try {
            if (env('USE_SQL_DB', false) && config('database.default') === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && $dbPath !== ':memory:' && !file_exists($dbPath)) {
                    @touch($dbPath);
                }

                if (!Schema::hasTable('assistance_requests')) {
                    Artisan::call('migrate', ['--force' => true]);
                }
            }
        } catch (\Throwable $e) {
            // swallow to avoid breaking boot in production
        }
    }
}
