<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 11:53
 */

namespace App\Impl\Repository;


use App\Impl\Repository\Permission\PermissionEloquent;
use App\Impl\Repository\Role\RoleEloquent;
use App\Impl\Repository\User\UserEloquent;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Impl\Repository\Permission\PermissionInterface', function() {
            return new PermissionEloquent(new Permission());
        });

        $this->app->bind('App\Impl\Repository\Role\RoleInterface', function() {
            return new RoleEloquent(new Role);
        });

        $this->app->bind('App\Impl\Repository\User\UserInterface', function(){
            return new UserEloquent(new User());
        });

    }
}