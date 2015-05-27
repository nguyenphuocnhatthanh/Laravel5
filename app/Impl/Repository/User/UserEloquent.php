<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 12:41
 */

namespace App\Impl\Repository\User;

use App\Impl\Repository\AbstractEloquentRepository;
use App\Models\User;

class UserEloquent extends AbstractEloquentRepository implements UserInterface {

    /**
     * App\Model\User
     * @var $user
     */
    protected $model;

    public function __construct(User $user){
        $this->model = $user;
    }

    /**
     * @return mixed
     */
    public function all(){
        return $this->make(['roles'])->get();
    }

    /**
     * Save data
     * @param $request
     * @param $rolesInput
     * @return mixed
     */
    public function save($request, $rolesInput)
    {
        if($request->has('id')) {
            $user = $this->getById($request->get('id'));
        }else {
            $user = new User();
            $user->password = \Hash::make('abcde');
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $bool = $user->save();

        if($bool) $user->roles()->sync($rolesInput);

        return $bool;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function checkEmail($email)
    {
        return $this->whereEloquent('email', $email)->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserRole($id){
        return $this->make(['roles' => function($q) {
            $q->get(['id', 'name']);
        }])->get(['id', 'name', 'email'])->find($id);
    }

    /**
     * @param $id
     * @param $email
     * @return mixed
     */
    public function checkEditEmail($id, $email)
    {
        return $this->whereEloquent('id', $id, '!=')->where('email', '=', $email)->get();
    }

    /**
     * @param $column
     * @param $data
     * @return mixed
     */
    public function whereIn($column,array $data)
    {
        return $this->model->whereIn($column, $data);
    }

    /**
     * @param $column
     * @param array $ids
     * @return mixed
     */
    public function restoreMultiRecord($column, array $ids){
        return $this->whereIn($column, $ids)->restore();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id){

        return $this->whereIn('id', [$id])->restore();
    }

    /**
     * @param int $adj
     * @param array $params
     * @return mixed
     */
    public function paginate($adj, array $params){
        if($params['sort'] == 'roles'){
            $users = $this->make(['roles'])->paginate($adj);
            foreach($users as $user){
                if($params['orderBy'] == 'desc'){
                    $user->roles->sortByDesc(function($role){ return $role->id;});
                }else{
                    $user->roles->sortBy(function($role){ return $role->id;});
                }
            }
            return $users;
        }

        return $this->checkSort($params)
            ? $this->make(['roles'])->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
            : $this->make(['roles'])->paginate($adj);
    }

    /**
     * @param int $adj
     * @param array $params
     * @return mixed
     */
    public function OnlyTrashedPaginate($adj, array $params)
    {
        if($params['sort'] == 'roles'){
            $users = $this->onlyTrashed()->paginate($adj);
            foreach($users as $user){
                if($params['orderBy'] == 'desc'){
                    $user->roles->sortByDesc(function($role){ return $role->id;});
                }else{
                    $user->roles->sortBy(function($role){ return $role->id;});
                }
            }

            return $users;
        }

        return  ($this->checkSort($params))
            ? $this->onlyTrashed()->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
            : $this->onlyTrashed()->paginate($adj);
    }


    /**
     * @param $optionDisplay
     * @param $search
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function search($optionDisplay, $search, $adj, array $params)
    {
        if($optionDisplay == 'displaydelete')
            return $this->checkSort($params)
                ? $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')
                    ->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
                : $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')->paginate($adj);

        return $this->checkSort($params)
                ? $this->whereEloquent('name', '%'.$search.'%', 'LIKE')
                ->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
                : $this->whereEloquent('name', '%'.$search.'%', 'LIKE')->paginate($adj);
    }

    /**
     * sort Role in users
     * @param $sort
     * @return mixed
     */
   /* public function sortPivotRoles($sort)
    {
        $users = $this->paginate('10', ['sort' => null, 'orderBy' => null]);

        foreach($users as $user){
            if($sort == 'desc'){
                 $user->roles->sortByDesc(function($role){ return $role->id;});
            }else{
                $user->roles->sortBy(function($role){ return $role->id;});
            }
        }

        return $users;
    }*/

}