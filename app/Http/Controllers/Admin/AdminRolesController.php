<?php namespace App\Http\Controllers\Admin;

use App\Helper\SortOrder;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Impl\Repository\Permission\PermissionInterface;
use App\Impl\Repository\Role\RoleInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Impl\Flash\FlashFacade as Flash;
use Illuminate\Http\Response;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class AdminRolesController extends Controller {

    use SortOrder;


    /**
     * App\User
     * @var $user
     */
    protected $user;

    /**
     * App\Impl\Repository\Role\RoleInterface
     * @var $role
     */
    protected $role;

    /**
     * App\Permission
     * @var $permission
     */
    protected $permission;

    function __construct(User $user,RoleInterface $role,PermissionInterface $permission)
    {
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index(Request $request){
        $permissions = $this->permission->all();
        $sort = $this->sort($request)['sort'];
        $orderBy = $this->sort($request)['orderBy'];
        $roles = $this->role->paginate(10, compact('sort', 'orderBy'));
        $roles->setPath('/admin/role');

        if($request->ajax()){
            if($request->get('option') == 'displaydelete')
                $roles = $this->role->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            if($request->get('search'))
                $roles = $this->role->search($request->get('option'),$request->get('search'), 10 , compact('sort', 'orderBy'));

            return \Response::json(view('admin.role.pagination', compact('roles', 'permissions'))
                ->with('search', $request->get('search'))
                ->with('selected', $request->get('selected'))->render());
        }

        if($request->has('search')){
            $roles = $this->role->search($request->get('option'),$request->get('search'), 10 , compact('sort', 'orderBy'));

            return view('admin.role.index', compact('roles', 'permissions'))
                ->with('search', $request->get('search'))
                ->with('selected', $request->get('option'));
        }

        if(Session::has('selected')) {
            $roles = $this->role->OnlyTrashedPaginate(10, compact('sort', 'order'));
            return view('admin.role.index', compact('roles', 'permissions'))->with('selected', Session::get('selected'));
        }



        return view('admin.role.index', compact('roles','permissions'));
    }

    public function postIndex(Request $request){

        $sort = $this->sort($request)['sort'];
        $orderBy = $this->sort($request)['orderBy'];

        if(is_array($request['selectedCheck']) && !empty($request['option'])) {
            foreach($request['selectedCheck'] as $id){
                $ids[] = $id;
            }
        }
        $permissions = $this->permission->all();
        if($request['option'] == 'delete') {

            if(empty($ids)){
                Flash::error('flash_notification.message', 'Chưa chọn mã muốn xóa');
                return \Redirect::back();
            }

            $this->role->whereIn('id', $ids)->delete();
            Flash::success('flash_notification.message', 'Xóa thành công');

            return redirect()->back();
        }elseif($request['option'] == 'displaydelete') {

            $roles = $this->role->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));

            return view('admin.role.index', compact('roles', 'permissions'))->with('selected', $request['option']);
        }elseif($request['option'] == 'displayall'){
            $roles = $this->role->paginate(10, compact('sort', 'orderBy'));
            return view('admin.role.index', compact('roles', 'permissions'))->with('selected', $request['option']);
        }elseif($request['option'] == 'restore' && isset($ids) && $ids != null){
            $this->role->whereIn('id', $ids)->restore();
            Flash::success('flash_notification.message', 'Restore data successfully');
            return redirect()->back()->with('selected', 'displaydelete');
        }
        return redirect()->back();
    }

    public function getCreate(){

        $permissions = $this->permission->all();
        $permissionsFinally = [];
        foreach($permissions as $permission){
            $tmp = explode('-', $permission->name);
            $displays[] = end($tmp);

        }
        $displays = array_values(array_unique($displays));


        foreach($displays as $display){
            for($i = 0; $i < count($permissions); $i++){

                $tmp = explode('-', $permissions[$i]->name);
                $tmp = end($tmp);
                if($tmp == $display){
                    $permissionsFinally[$display][] = $permissions[$i];
                }
            }
        }

        return view('admin.role.create', compact('permissions', 'permissionsFinally'));
    }

    public function postCreate(Requests\RoleFormRule $request){

        if( ! $this->role->save($request, Permission::preparePermissionsForSave($request->get('permissions')))) {
            Flash::error('flash_notification.message', 'Failed saved');
        }else {
            Flash::success('flash_notification.message', 'Add the Role successfully');
        }

        return redirect('admin/role');
    }



    public function delete($id, Request $request){
        if($request->isMethod('GET') && $request->ajax()) {
            if($this->role->delete($id)) {
                return \Response::json(['status' => true]);
            }
        }

        if($this->role->delete($id) != 0){
            Flash::success('flash_notification.message', 'Delete the Role successfulyy');
        }
        else Flash::error('flash_notification.message', 'Failed saved');

        return redirect('admin/role');
    }

    public function getEdit($id){
        $role = $this->role->getById($id);
        $permissions = Permission::preparePermissionsForDisplay($role->perms()->get());

        $permissionsFinally = [];

        foreach($permissions as $permission){
            $tmp = explode('-', $permission['name']);
            $displays[] = end($tmp);

        }

        $displays = array_values(array_unique($displays));

        foreach($displays as $display){
            for($i = 0; $i < count($permissions); $i++){

                $tmp = explode('-', $permissions[$i]['name']);
                $tmp = end($tmp);
                if($tmp == $display){
                    $permissionsFinally[$display]['data'][] = $permissions[$i];
                }
            }
        }

        foreach($permissionsFinally as $key => $permissions){
            $i = 0;
            array_walk($permissions['data'], function($values) use(&$i){
               if(array_key_exists('checked', $values)){
                   $i++;
               }
            });

            if($i == count($permissions['data'])){
               $permissionsFinally[$key]['checkAll'] = true;
            }
        }

        return \Response::json(view('admin.role.edit', compact('role', 'permissions', 'permissionsFinally'))->render());
    }

    public function postEdit(Requests\RoleFormRule $request){
        if($request->ajax()){
            if($this->role->save($request, Permission::preparePermissionsForSave($request->get('permissions')))){
                return \Response::json(['data' => $this->role->getById($request->get('id'))]);
            }
            return 'false';
        }

        if( ! $this->role->save($request, Permission::preparePermissionsForSave($request->get('permissions')))) {
            Flash::error('flash_notification.message', 'Failed saved');
        }else {
            Flash::success('flash_notification.message', 'Add the Role successfully');
        }

        return redirect('admin/role');
    }

    public function checkName(Request $request){
        return $role = $this->role->checkName($request->get('id'), $request->get('name')) ? 'true' : 'false';
    }

    public function restore($id, Request $request){

        if($request->ajax()) {
            if($this->role->restore($id)) return 'true';
            return 'false';
        }
        if($this->role->restore($id)) {
            Flash::success('flash_notification.message', 'Restore successfully');
            return redirect()->back()->with('selected', 'displaydelete');
        }

        Flash::error('flash_notification.message', 'Failed restore');
        return redirect()->back();
    }

}
