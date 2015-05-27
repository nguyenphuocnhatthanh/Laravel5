<?php namespace App\Impl\Repository\Role;
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 12:48
 */
use App\Http\Requests\RoleFormRule;
use App\Impl\Repository\Repository;
interface RoleInterface extends Repository{

    /**
     * Insert or Edit data Role
     * @param RoleFormRule $request
     * @return mixed
     */
    public function save($request, $preparePermissionsForSave);

    /**
     * @param $column
     * @param $values
     * @return mixed
     */
    public function whereIn($column, $values);

    /**
     * @param $id
     * @param $name
     * @return mixed
     */
    public function checkName($id, $name);

    /**
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function paginate($adj, array $params);

    /**
     * @param $display
     * @param $search
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function search($display, $search, $adj, array $params);

    /**
     * @param $id
     * @return mixed
     */
    public function restoreAssignedPermissionForRole($id);

    /**
     * Entrust restore
     * @param $id
     * @return mixed
     */
    public function restore($id);
}