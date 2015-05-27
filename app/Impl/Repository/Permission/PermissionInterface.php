<?php
namespace App\Impl\Repository\Permission;

use App\Http\Requests\FormPermissionCreate;
use App\Impl\Repository\Repository;

interface PermissionInterface extends Repository{
    /**
     * @param $request
     * @return mixed
     */
    public function save($request);

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
     * @param $column
     * @param $data
     * @return mixed
     */
    public function whereIn($column, $data);

    /**
     * @param $display
     * @param $search
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function search($display, $search, $adj,array $params);

    /**
     * Eloqent Restore
     * @param int $id
     * @return mixed
     */
    public function restore($id);
}