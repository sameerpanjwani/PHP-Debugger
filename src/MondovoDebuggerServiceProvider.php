<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 9/25/2018
 * Time: 12:09 PM
 */

namespace Mondovo\Debugger;

use Mondovo\Debugger\Contracts\DebuggerInterface;
use Illuminate\Support\ServiceProvider;

class MondovoDebuggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*$this->publishes([
            __DIR__.'/config/debugger.php' => \config_path('debugger.php'),
        ], 'config');*/
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //$this->mergeConfigFrom(__DIR__.'/config/debugger.php', 'debugger');
        //$this->app->bind(DebuggerInterface::class, 'Mondovo\DataTable\Debugger');
    }
}