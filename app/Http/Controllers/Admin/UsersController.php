<?php namespace App\Http\Controllers\Admin;

use App\Helper\SortOrder;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Impl\Repository\Role\RoleInterface;
use App\Impl\Repository\User\UserInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;


class UsersController extends Controller {

    use SortOrder;
    /*
     * App\Impl\Repository\User\UserInterface
     * @var $user
     */
    protected $user;
    /**
     * @var RoleInterface
     */
    protected $role;

    function __construct(UserInterface $user, RoleInterface $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function index(Request $request){
        $sort = $this->sort($request)['sort'];
        $orderBy = $this->sort($request)['orderBy'];

      /*  if($sort == 'roles') {
            $users = $this->user->sortPivotRoles($orderBy);
        }else{*/
        $users = $this->user->paginate(10, compact('sort', 'orderBy'));

        //}

        if($request->ajax()) {
            if($request->get('option') == 'displaydelete'){
                $users = $this->user->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            }
            if($request->has('search') ){
                $users = $this->user->search(
                    $request->get('option'), $request->get('search'),10, compact('sort', 'orderBy')
                );
            }

            return \Response::json(view('admin.user.pagination', compact('users'))
                ->with('search', $request->get('search'))->with('selected', $request->get('option'))->render());
        }

        if(\Session::has('selected')) {
            $users = $this->user->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            return view('admin.user.index', compact('users'))->with('selected', \Session::get('selected'));
        }

        if($request->has('search')){
            $users = $this->user->search($request->get('option'), $request->get('search'),
                10, compact('sort', 'orderBy'));

            return view('admin.user.index', compact('users'))
                ->with('selected', $request->get('option'))->with('search', $request->get('search'));
        }

        return view('admin.user.index', compact('users'));
	}

    public function postIndex(Request $request){
        $sort = null;
        $orderBy = null;

        if(is_array($request['selectedCheck']) && !empty($request['option'])) {
            foreach($request['selectedCheck'] as $id){
                $ids[] = $id;
            }
        }

        if($request['option'] == 'delete') {
            if(empty($ids)){
                \Flash::error('flash_notification.message', 'Chưa chọn mã muốn xóa');
                return \Redirect::back();
            }

            $this->user->whereIn('id', $ids)->delete();
            \Flash::success('flash_notification.message', 'Xóa thành công');

            return redirect()->back();
        }elseif($request['option'] == 'displaydelete') {

            $users = $this->user->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            $users->setPath('/admin/users');

            return view('admin.user.index', compact('users'))->with('selected', $request['option']);
        }elseif($request['option'] == 'displayall'){
            $users = $this->user->paginate(10, compact('sort', 'orderBy'));
            $users->setPath('/admin/users');

            return view('admin.user.index', compact('users'))->with('selected', $request['option']);
        }elseif($request['option'] == 'restore' && isset($ids) && $ids != null){
            if($this->user->restoreMultiRecord('id', $ids) > 0){

                \Flash::success('flash_notification.message', 'Restore data successfully');
                return redirect()->back()->with('selected', 'displaydelete');
            }
            \Flash::error('flash_notification.message', 'Restore data Failed');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getCreate(){
        $roles = $this->role->lists('name', 'id');
        return view('admin.user.create', compact('roles'));
    }

    public function postCreate(Requests\FormUsers $request) {

        if($this->user->save($request, Role::prepareRolesForSave($request->get('roles')))) {
            \Flash::success('flash_notification.message', 'Edit successfully');
            return redirect('/admin/users');
        }

        \Flash::error('flash_notification.message', 'Failed Edit');
        return redirect()->back();
    }

    public function getEdit($id, Request $request){
        if($request->ajax()) {
            $user = $this->user->getById($id);
            $roles = $this->role->lists('name', 'id');
            return \Response::json(view('admin.user.edit', compact('user', 'roles'))->render());
        }

        return \App::abort(403, 'Unauthorized action');
    }



    public function postEdit(Requests\FormUsers $request){
        if($request->ajax()) {
            if($this->user->save($request, Role::prepareRolesForSave($request->get('roles')))) {
                return \Response::json(['data' => $this->user->getUserRole($request->get('id'))]);
            }

            return \Response::json(['data' => 'failed']);
        }

        return \App::abort(403, 'Unauthorized action');
    }

    public function delete($id, Request $request){
        if($request->ajax()) {
            if($this->user->delete($id)){
                return \Response::json(['status' => true]);
            }
            return \Response::json(['status' => false]);
        }

        if($this->user->delete($id)){
            \Flash::success('flash_notification.message', 'Delete  successfully');
            return redirect('/admin/users');
        }

        \Flash::error('flash_notification.message', 'Failed Edit');
        return redirect()->back();
    }

    public function restore($id, Request $request){
        if($request->ajax()){
            if($this->user->restore($id)) return 'true';
            return 'false';
        }

        if($this->user->restore($id)) {
            \Flash::success('flash_notification.message', 'Restore successfully');
            return redirect()->back()->with('selected', 'displaydelete');
        }

        \Flash::error('flash_notification.message', 'Failed restore');
        return redirect()->back();
    }

    public function test($id){
        $temp = User::with(['roles' => function($q) {
            $q->get(['id', 'name']);
        }])->get(['id', 'name'])->find($id);


        $users = $this->user->getUserRole($id);
        dd( $users->toArray());
    }

    public function checkEmail(Request $request){
        if($request->ajax()) {
            if(count($this->user->checkEmail($request->get('email'))) != 0) return 'false';
            return 'true';
        }
        return \App::abort(403, 'Unauthorized action');
    }

    public function checkEditEmail(Request $request){
       // dd();
        //return  User::query()->where('id', '!=', $request->get('id'))->where('email', '=', $request->get('email'))->get();
       // return count($this->user->checkEditEmail($request->get('id'), $request->get('email')));
        if($request->ajax()){
            if(count($this->user->checkEditEmail($request->get('id'), $request->get('email'))) !=0)
                return 'false';
            return 'true';
        }

        return \App::abort(403, 'Unauthorized action');
    }
}
