<?php  namespace App\Impl\Repository;
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 07/04/2015
 * Time: 18:02
 */
abstract class AbstractEloquentRepository {

    /**
     * Get all data
     * @return mixed
     */
    public function all(){
        return $this->model->all();
    }

    /**
     * Remove data
     * @param $id
     * @return mixed
     */
    public function delete($id){
        $model = $this->getById($id);
        return $model->delete();
    }

    /**
     * Get one data
     * @param $id
     */
    public function getById($id){
        return $this->model->findOrFail($id);
    }

    /**
     * Eager loading Eloquent
     * @param array $with
     * @return mixed
     */
    public function make(array $with = []){
        return $this->model->with($with);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function lists($key, $value)
    {
       return $this->all()->lists($key, $value);
    }

    /**
     * @param $column
     * @param $values
     * @param string $operator
     * @return mixed
     */
    public function whereEloquent($column, $values, $operator = '=')
    {
        return $this->model->where($column, $operator, $values);
    }

    /**
     * Paginator Eloquent
     * @param int $adj
     * @param array $params : key['sort', 'orderBy']
     * @return mixed
     */
    public function paginate($adj, array $params)
    {
        return ($this->checkSort($params))
                ? $this->model->query()->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
                : $this->model->query()->paginate($adj);
    }

    /**
     * Eloquent onlyTrashed
     * @return mixed
     */
    public function onlyTrashed(){
        return $this->model->query()->onlyTrashed();
    }

    /**
     * Display data softDeleted combine Paginate
     * @param int $adj : perPage
     * @param array $params : key['sort', 'orderBy']
     * @return mixed
     */
    public function OnlyTrashedPaginate($adj, array $params)
    {
        return  ($this->checkSort($params))
                ? $this->onlyTrashed()->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
                : $this->onlyTrashed()->paginate($adj);

    }

    /**
     * check condition is name exists
     * @param $id
     * @param $name
     * @return bool
     */
    public function checkName($id, $name){
        if($id == 0 && $id == null) $model = $this->model->query()->where('name', '=', $name);
        else $model = $this->model->query()->where('name', '=', $name)->where('id', '!=', $id)->get();
        if($model->count() != 0) {
            return false;
        }

        return true;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function checkSort(array $params)
    {
        return $params['sort'] != null && $params['orderBy'];
    }


}