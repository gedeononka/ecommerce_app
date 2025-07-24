<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Autres configurations...
    }
    
    public function register(): void
    {
        // Enregistrer l'alias pour PDF
        $this->app->alias('dompdf.wrapper', \Barryvdh\DomPDF\Facade\Pdf::class);
    }
}