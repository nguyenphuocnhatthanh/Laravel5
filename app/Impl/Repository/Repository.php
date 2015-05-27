<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 11:48
 */

namespace App\Impl\Repository;


interface Repository {
    /**
     * Get all data
     * @return mixed
     */
    public function all();

    /**
     * Remove data
     * @param $id
     * @return mixed
     */public function delete($id);

    /**
     * Get one data
     * @param $id
     * @return mixed
     */public function getById($id);

    /**
     * Eager Loading Repository
     * @param array $with : name table
     */
    public function make(array $with = []);

    /**
     * Display data softDeleted combine Paginate
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function onlyTrashed();

    /**
     * @param $adjPaginate
     * @param array $paramsSort
     * @return mixed
     */
    public function OnlyTrashedPaginate($adjPaginate,array $paramsSort);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function lists($key, $value);
}