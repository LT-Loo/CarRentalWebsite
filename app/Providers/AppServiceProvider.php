<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
        // View()->composer('*', function($view) {
        //     $cars = File::json(base_path("storage/cars.json"));
        //     $car_types = [];
        //     $car_brands = [];
        //     foreach ($cars as $car) {
        //         $car_types[$car["type"]][] = $car;
        //         $car_brands[$car["brand"]][] = $car;
        //     }
        //     ksort($car_types);
        //     ksort($car_brands);

        //     $view->with('cars', $cars)->with('car_types', $car_types)->with('car_brands', $car_brands);
        // });
    }
}
