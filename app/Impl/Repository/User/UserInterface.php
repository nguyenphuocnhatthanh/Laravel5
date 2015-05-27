<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 12:40
 */

namespace App\Impl\Repository\User;

use App\Impl\Repository\Repository;

interface UserInterface extends Repository {

    /**
     * Save data
     * @param $request
     * @param $rolesInput
     * @return mixed
     */
    public function save($request, $rolesInput);

    /**
     * Check email exists
     * @param $email
     * @return mixed
     */
    public function checkEmail($email);

    /**
     * check edit email exists
     * @param $id
     * @param $email
     * @return mixed
     */
    public function checkEditEmail($id, $email);

    /**
     * @param $id
     * @return mixed
     */
    public function getUserRole($id);

    /**
     * Eloquent wherein
     * @param $column
     * @param array $data
     * @return mixed
     */
    public function whereIn($column,array $data);

    /**
     * Eloquent restore
     * @param $id
     * @return mixed
     */
    public function restore($id);

    /**
     * restore multi record
     * @param $column
     * @param array $ids
     * @return mixed
     */
    public function restoreMultiRecord($column, array $ids);

    /**
     * Eloquent paginate
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function paginate($adj, array $params);

    /**
     * Search data
     * @param $optionDisplay
     * @param $search
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function search($optionDisplay, $search, $adj, array $params);

    /**
     * sort Role in users
     * @param $sort
     * @return mixed
     */
//    public function sortPivotRoles($sort);
}