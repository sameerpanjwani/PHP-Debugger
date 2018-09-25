<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 9/25/2018
 * Time: 12:09 PM
 */

namespace Mondovo\Debugger;

use Illuminate\Support\ServiceProvider;
use Mondovo\Debugger\Contracts\DebuggerInterface;

class MondovoDebuggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/debugger.php' => \config_path('debugger.php'),
        ], 'config');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/debugger.php', 'debugger');
        $this->app->bind(DebuggerInterface::class, 'Mondovo\Debugger\Helpers\Debugger');

    }
}