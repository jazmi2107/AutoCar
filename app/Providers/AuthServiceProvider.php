<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Extensions\FirebaseUserProvider;
use App\Models\FirebaseUser;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::provider('firebase', function ($app, array $config) {
            try {
                return new FirebaseUserProvider($app['firebase.auth'], FirebaseUser::class);
            } catch (\Exception $e) {
                // If Firebase fails to initialize (e.g. missing credentials), 
                // we return the provider anyway but it will handle failures gracefully.
                // We use a proxy or just pass null if we have to, but better to handle inside provider.
                \Log::error('Firebase Auth Provider failed to initialize: ' . $e->getMessage());
                
                // Return a dummy/empty provider or the real one with a flag?
                // For now, let's try to resolve it lazily inside the provider instead.
                return new FirebaseUserProvider(null, FirebaseUser::class);
            }
        });
    }
}
