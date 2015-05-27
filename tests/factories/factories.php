<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 29/03/2015
 * Time: 14:45
 */

$factory('App\Models\User', [
    'name'  => $faker->userName,
    'email' => $faker->email,
    'password'  => Hash::make('abcde'),
]);