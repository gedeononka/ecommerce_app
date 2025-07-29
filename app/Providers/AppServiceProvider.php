<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Autres configurations...
        // API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

    }
    
    public function register(): void
    {
        // Enregistrer l'alias pour PDF
        $this->app->alias('dompdf.wrapper', \Barryvdh\DomPDF\Facade\Pdf::class);
    }
}