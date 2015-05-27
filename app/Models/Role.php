<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

	use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Save role Pivot
     * @param $rolesInput
     * @return array
     */
    public static function prepareRolesForSave($rolesInput){
        $roles = static::all();
        $prepareRoles = [];

        foreach($rolesInput as $roleId){
            array_walk($roles, function(&$value) use ($roleId, &$prepareRoles){
                if($value = (int) $roleId) {
                    $prepareRoles[] = $roleId;
                }
            });
        }

        return $prepareRoles;
    }

    /**
     * Display role Pivot
     * @param $roles
     * @return array
     */
    public static function prepareRolesForDisplay($roles){
        $prepareRoles = static::all()->toArray();

        foreach($roles as $role) {
            array_walk($prepareRoles, function(&$value) use(&$role){
                if($role->name == $value['name'])
                    $value['checked'] = true;
            });
        }

        return $prepareRoles;
    }
}
