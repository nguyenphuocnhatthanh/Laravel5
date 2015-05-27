<?php namespace App\Models;

use bar\baz\source_with_namespace;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends EntrustPermission {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function preparePermissionsForSave($permissionsInput){
        //$permissions = $this->all()->toArray();
        $permissions = self::all()->toArray();
        $preparePermission = [];

        foreach($permissionsInput as $key => $permission){
            array_walk($permissions, function(&$value) use (&$permission, &$preparePermission, &$key){

                if($permission == (int) $value['id']) {
                    $preparePermission[] = $permission;
                }elseif($key == (int) $value['id'] && $permission == 'on' ) {
                    $preparePermission[] = $key;
                }


            });
        }

        return $preparePermission;
    }

    public static function preparePermissionsForDisplay($permissions){
        $preparePermissions =  self::all()->toArray();
        foreach($permissions as $permission){
            array_walk($preparePermissions, function(&$value) use (&$permission){
                if($permission->name == $value['name']){
                    $value['checked'] = true;
                }

            });
        }

        return $preparePermissions;
    }

}
