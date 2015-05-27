<?php namespace App\Impl\Flash;
use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 13:59
 */
class FlashServiceProvider extends ServiceProvider{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('flash', 'App\Impl\Flash\FlashNotifier');
    }

}