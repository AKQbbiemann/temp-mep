<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('load_sum', function ($attribute, $value, $parameters, $validator) {
            $sum = 0;
            foreach ($parameters as $key => $attributeName) {
                $attributeValue = $validator->getData()[$attributeName];
                $sum += $attributeValue;
            }
            $sum += $value;

            return $sum == 100;
        });
    }
}
