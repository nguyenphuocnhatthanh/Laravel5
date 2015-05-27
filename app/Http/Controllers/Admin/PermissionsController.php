<?php namespace App\Http\Controllers\Admin;

use App\Events\CreateFileToDB;
use App\Helper\SortOrder;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Impl\Repository\Permission\PermissionInterface;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PermissionsController extends Controller {

    use SortOrder;

    /**
     * @var PermissionInterface
     */
    protected $permission;

    /**
     * @param PermissionInterface $permssion
     */
    function __construct(PermissionInterface $permssion)
    {
        $this->permission = $permssion;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request){
        $sort = $this->sort($request)['sort'];
        $orderBy = $this->sort($request)['orderBy'];
        $permissions = $this->permission->paginate(10,compact('sort', 'orderBy'));
        $permissions->setPath('/admin/permission');

        if($request->ajax()){
            if($request->get('option') == 'displaydelete'){
                $permissions = $this->permission->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            }
            if($request->has('search') ){
                $permissions = $this->permission->search(
                    $request->get('option'), $request->get('search'),10, compact('sort', 'orderBy')
                );
            }

            return \Response::json(view('admin.permission.pagination', compact('permissions'))
                    ->with('search', $request->get('search'))->with('selected', $request->get('option'))->render());
        }

        if($request->has('search')){

            $permissions = $this->permission->search(
                $request->get('option'), $request->get('search'),10, compact('sort', 'orderBy')
            );

            return view('admin.permission.index', compact('permissions'))
                ->with('selected', $request->get('option'))->with('search', $request->get('search'));
        }

        if(Session::has('selected')){
            $permissions = $this->permission->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            return view('admin.permission.index', compact('permissions'))->with('selected', Session::get('selected'));
        }


        return view('admin.permission.index', compact('permissions'));
	}

    /**
     * Post Option
     * @param Request $request
     * @return $this
     */
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

            $this->permission->whereIn('id', $ids)->delete();
            \Flash::success('flash_notification.message', 'Xóa thành công');

            return redirect()->back();
        }elseif($request['option'] == 'displaydelete') {

            $permissions = $this->permission->OnlyTrashedPaginate(10, compact('sort', 'orderBy'));
            $permissions->setPath('/admin/permission');

            return view('admin.permission.index', compact('permissions'))->with('selected', $request['option']);
        }elseif($request['option'] == 'displayall'){
            $permissions = $this->permission->paginate(10, compact('sort', 'orderBy'));
            $permissions->setPath('/admin/permission');

            return view('admin.permission.index', compact('permissions'))->with('selected', $request['option']);
        }elseif($request['option'] == 'restore' && isset($ids) && $ids != null){
            if($this->permission->OnlyTrashedPaginate(10, compact('sort', 'orderBy')) != null)
            {
                $this->permission->whereIn('id', $ids)->restore();
                \Flash::success('flash_notification.message', 'Restore data successfully');
                return redirect()->back()->with('selected', 'displaydelete');
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getCreate(){
        return view('admin.permission.create');
    }

    /**
     * @param Requests\FormPermission $form
     * @return Redirect
     */
    public function postCreate(Requests\FormPermission $form){
        if(!$this->permission->save($form)) {
            \Flash::error('flash_notification.message', 'Failed saved');
        }else{
            \Flash::success('flash_notification.message', 'Add the Permission successfully');
        }

        return redirect('admin/permission');
    }

    /**
     * @param $id
     * @param Request $request
     * @return Redirect
     */
    public function getDelete($id, Request $request){
        if($request->isMethod('GET') && $request->ajax()) {
            if($this->permission->delete($id)) {
                return \Response::json(['status' => true]);
            }
        }

        if($this->permission->delete($id)) {
            \Flash::success('flash_notification.message', 'Delete the Permission successfully');
        }else{
            \Flash::error('flash_notification.message', 'Failed delete');
        }

        return redirect('admin/permission');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getEdit($id){
        $permission = $this->permission->getById($id);
        return view('admin.permission.edit', compact('permission'));
    }

    /**
     * @param Requests\FormPermission $request
     * @return Redirect|string
     */
    public function postEdit(Requests\FormPermission $request){

        if($request->ajax() && $request->get('_token') == session('_token')){
            if($this->permission->save($request)) {
                return \Response::json([
                    'data' => $this->permission->getById($request->get('id'))
                ]);
            }else return 'false';
        }

        if( $this->permission->save($request)) {
            \Flash::success('flash_notification.message', 'Edit the Permission successfully');
        }else{
            \Flash::error('flash_notification.message', 'Failed Edit');
        }

        return redirect('admin/permission');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function checkName(Request $request){
        return $this->permission->checkName($request->get('id'), $request->get('name')) ? 'true' : 'false';
    }

    public function restore($id, Request $request){
        if($request->ajax()){
            if($this->permission->restore($id)) return 'true';
            return 'false';
        }

        if($this->permission->restore($id)){
            \Flash::success('flash_notification.message', 'Edit the Permission successfully');
            return redirect()->back()->with('selected', 'displaydelete');
        }

        \Flash::error('flash_notification.message', 'Failed Edit');
        return redirect()->back();
    }


}
