<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminsController extends Controller {

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('admin.index');
	}

}
