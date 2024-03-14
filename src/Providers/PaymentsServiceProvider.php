<?php

namespace Narfu\Payments\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Narfu\Payments\Commands\CheckPayments;
use Narfu\Payments\Http\Livewire\NarfuPayment;
use Narfu\Payments\Http\Livewire\ui\kit\NarfuAutocompete;
use Narfu\Payments\Http\Livewire\ui\kit\NarfuSelect;

class PaymentsServiceProvider extends ServiceProvider
{
    public static function getMigrationPath(): string
    {
        return __DIR__ . '/../../database/migrations';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config/payments.php', 'narfu-payments');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws \Exception
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/../../resources/logo' => public_path('narfu/payments/logo'),
        ], 'narfu-payments');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/payments.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'narfu-payments');
        $this->registerLivewireComponents();
        $this->registerCommands();

        if (App::runningInConsole()) {
            $this->app->booted(function () {
            });
        }
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('narfu-payment', NarfuPayment::class);
        Livewire::component('narfu-autocomplete', NarfuAutocompete::class);
        Livewire::component('narfu-select', NarfuSelect::class);
    }

    /**
     * Регистрация консольных команд
     */
    private function registerCommands()
    {
        $this->commands([
            CheckPayments::class,
        ]);
    }
}
