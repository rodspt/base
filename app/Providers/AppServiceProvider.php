<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register(): void
  {
    if ($this->app->environment('local')) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
    }
      if (!$this->app->environment('production')) {
          $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
      }
  }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //JsonResource::withoutWrapping(); // Remove this if you want to wrap data within a 'data' key
    }
}
