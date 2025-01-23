<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use App\Models\Album;
use App\Policies\AlbumPolicy;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Filesystem::class, CustomFilesystem::class);  
    
        Gate::policy(Album::class, AlbumPolicy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
