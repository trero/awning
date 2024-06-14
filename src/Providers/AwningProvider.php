<?php
/**
 * Created by PhpStorm.
 * User: luigi
 * Date: 14/06/2024
 * Time: 16:40
 */

namespace Trero\Awning\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AwningProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Log::info('booted');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'awning');
    }
}