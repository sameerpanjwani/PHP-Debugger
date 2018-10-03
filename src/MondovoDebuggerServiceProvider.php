<?php
/**
 * Created by PhpStorm.
 * User: maifo
 * Date: 9/25/2018
 * Time: 12:09 PM
 */

namespace Mondovo\Debugger;

use Illuminate\Support\ServiceProvider;
use Mondovo\Debugger\Contracts\ActivityLog\ActivityLogDisplayServiceInterface;
use Mondovo\Debugger\Contracts\DebuggerInterface;
use Mondovo\Debugger\Contracts\DebuggerLogRepositoryInterface;

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
        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
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
        $this->app->bind(ActivityLogDisplayServiceInterface::class, 'Mondovo\Debugger\Services\ActivityLogDisplayService');
        $this->app->bind(DebuggerLogRepositoryInterface::class, 'Mondovo\Debugger\Repositories\Db\DbDebuggerLogRepository');

    }
}