<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * pattern Route
 */
Route::pattern('id', '[\d]+');

/**--------------------------
 *  Role Route
 * --------------------------
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'auth'], function() {

    /*
     * Admin Route
     */
    get('/', 'AdminsController@index');
    /*
     * Role Route
     */
    get('role', ['as' => 'role', 'uses' => 'AdminRolesController@index']);
    get('role/create', 'AdminRolesController@getCreate');
    get('role/edit/{id}', 'AdminRolesController@getEdit');
    get('role/delete/{id}', 'AdminRolesController@delete');
    get('role/restore/{id}', 'AdminRolesController@restore');

    post('role/checkname', 'AdminRolesController@checkName');
    post('role', 'AdminRolesController@postIndex');
    post('role/create', 'AdminRolesController@postCreate');
    post('role/edit', 'AdminRolesController@postEdit');

    /*
     * Permission Route
     */
    get('permission', ['as' => 'permission', 'uses' => 'PermissionsController@index']);
    get('permission/create', 'PermissionsController@getCreate');
    get('permission/edit/{id}', 'PermissionsController@getEdit');
    get('permission/delete/{id}', 'PermissionsController@getDelete');
    get('permission/restore/{id}', 'PermissionsController@restore');

    post('permission', 'PermissionsController@postIndex');
    post('permission/create', 'PermissionsController@postCreate');
    post('permission/edit', 'PermissionsController@postEdit');
    post('permission/checkname', 'PermissionsController@checkName');

    /*
     * User Route
     */
    get('users', ['as' => 'admin.users', 'uses' => 'UsersController@index']);
    get('users/create', 'UsersController@getCreate');
    get('users/edit/{id}', 'UsersController@getEdit');
    get('users/delete/{id}', 'UsersController@delete');
    get('users/restore/{id}', 'UsersController@restore');
    get('users/checkEmail', 'UsersController@checkEmail');
    get('users/checkEditEmail', 'UsersController@checkEditEmail');
    get('users/{id}', 'UsersController@test');

    post('users/create', 'UsersController@postCreate');
    post('users/edit', 'UsersController@postEdit');
    post('users', 'UsersController@postIndex');
});

get('test', function(\Illuminate\Http\Request $request){
    $user = \App\Models\User::find(1);
    dd($user->ability(['admin'], ['test']), array_get($request->route()->getAction(), 'any', false));
});



Route::get('home', 'HomeController@index');
get('/', ['uses' =>'HomeController@index', 'permission' => 'temp']);
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


/**
 * Entrust Filter
 */


/*\Entrust::routeNeedsRole('admin', ['admin', 'manage-post', 'manage-product', 'manage-role', 'manage-user', 'mod'], null, false);
\Entrust::routeNeedsRoleOrPermission('admin/role*', ['admin', 'mod', 'manage-role'], ['create-role', 'read-role', 'edit-role', 'delete-role'], null, false);
\Entrust::routeNeedsRoleOrPermission('admin/users*', ['admin','mod', 'manage-users'], ['create-users', 'read-users', 'edit-users', 'delete-users'], null, false);*/

//\Entrust::routeNeedsRole('admin/users*', 'manage-users',  Redirect::to('/'));
