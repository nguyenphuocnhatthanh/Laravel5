<?php  namespace App\Impl\Repository\Permission;
use App\Events\CreateFileToDB;
use App\Http\Requests\FormPermissionCreate;
use App\Impl\Repository\AbstractEloquentRepository;
use App\Models\Permission;
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 09/04/2015
 * Time: 16:32
 */
class PermissionEloquent extends AbstractEloquentRepository implements PermissionInterface{
    /*
     * Permission $model
     */
    protected $model;

    function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function save($request)
    {
        if($request->has('id')) {
            $permission = $this->getById($request->get('id'));
        }else {
            $permission = new Permission;
        }

        $permission->name = $request->get('name');
        $permission->display_name = $request->get('display_name');
        $permission->description = $request->get('description');
        $bool = $permission->save();
        event(new CreateFileToDB('permission.json', $this->all()));
        return $bool;
    }

    public function delete($id)
    {
        $bool = parent::delete($id);
        if($bool){
            event(new CreateFileToDB('permission.json', $this->all()->toJSON()));
        }

        return $bool;
    }

    public function whereIn($column, $data)
    {
        return $this->model->whereIn($column, $data);
    }

    /**
     * Eloqent Restore
     * @return mixed
     */
    public function restore($id)
    {
        return $this->whereIn('id', [$id])->restore() ? true : false;
    }


    public function whereByName($search)
    {
        return $this->model->whereEloquent('name', 'LIKE', '%'.$search.'%');
    }

    public function search($display, $search, $adj, array $params)
    {
        //dd($display, $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')->paginate($adj));
        if($display == 'displaydelete')
            return $this->checkSort($params)
                ? $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')
                    ->orderBy($params['sort'], $params['orderBy'])
                    ->paginate($adj)
                : $this->onlyTrashed()->where('name', 'LIKE', '%'.$search.'%')->paginate($adj);

        return ($this->checkSort($params))
            ?  $this->whereByName($search)->orderBy($params['sort'], $params['orderBy'])->paginate($adj)
            :  $this->whereByName($search)->paginate($adj);
    }


}