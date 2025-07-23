<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void{
   
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
  $this->spinUpApi();
    }
    protected function spinUpApi(){
        
        Route::prefix("api")
        ->middleware("api")
        ->group(base_path("routes/api.php"));
    }
}
