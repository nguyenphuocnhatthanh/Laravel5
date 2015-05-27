<?php  namespace App\Impl\Repository\Role;
use App\Http\Requests\RoleFormRule;

/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 12:53
 */

use App\Impl\Repository\AbstractEloquentRepository;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleEloquent extends AbstractEloquentRepository implements RoleInterface{

    /**
     * App\Model\Role
     * @var $role
     */
    protected $model;

    /**
     * Contructor
     * @param $role
     */
    function __construct(Role $role)
    {
        $this->model = $role;
    }



    /**
     * Insert or Edit data Role
     * @param RoleFormRule $request
     * @return mixed
     */
    public function save($request, $preparePermissionsForSave)
    {
        if( ! $request->has('id')) {
            $role = new Role();
        } else {
            $role = $this->getById($request->get('id'));
        }

        $role->name = $request['name'];
        $role->display_name = $request['display_name'];
        $role->description = $request['description'];
        $role->save();

        $role->perms()->sync($preparePermissionsForSave);
        return $role->id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restoreAssignedPermissionForRole($id)
    {
       // $role = $this->getById($id);

    }


    /**
     * @param $column
     * @param $values
     * @return mixed
     */
    public function whereIn($column, $values)
    {
        return $this->model->whereIn($column, $values);
    }

    /**
     * Entrust restore
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->whereIn('id', [$id])->restore() ? true : false;
    }


    /**
     * @param $display
     * @param $search
     * @param $adj
     * @param array $params
     * @return mixed
     */
    public function search($display, $search, $adj, array $params)
    {
        if($display == 'displaydelete'){
            return $this->checkSort($params)
                ? $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
                : $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')->paginate($adj);
        }

        return $this->checkSort($params)
            ? $this->whereByName($search)->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
            : $this->whereByName($search)->paginate($adj);
    }

    /**
     * @param $search
     * @return mixed
     */
    public function whereByName($search)
    {
        return $this->whereEloquent('name', '%' . $search . '%', 'LIKE');
    }


}