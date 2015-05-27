<?php  namespace App\Providers;
use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: ron
 * Date: 08/04/2015
 * Time: 10:58
 */
class ViewComposerServiceProvider extends ServiceProvider {


    public function boot()
    {
        $this->composerSelectOption();
    }

    public function composerSelectOption(){
        view()->composer([
            'admin.role.index', 'admin.permission.index', 'admin.user.index'
        ],'App\Http\ComposerView\SelectOptionComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}