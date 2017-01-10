<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Ticket;
use App\User;

class UsersController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = FALSE) {
        
        $users = User::getUsers();
//        var_dump($users);die();
        return view('staff.users', ['users' => $users]);
    }
    
    public function create() {
        
        $maint_buildings = $maint_regions = $maint_groups = array();
        
        $users = User::getUsers();
        $regions = DB::table('regions')
                ->get();

        foreach ($regions as $region) {
            $maint_regions[$region->region_id] = $region->region_name;
        }
        $buildings = DB::table('buildings')
                ->get();
        foreach ($buildings as $building) {
            $maint_buildings[$building->building_id] = $building->building_name;
        }
        $groups = DB::table('users_groups')
                ->get();
        foreach ($groups as $group) {
            $maint_groups[$group->group_id] = $group->group_name;
        }

        return view('staff.adduser', ['regions' => $maint_regions, 'groups' => $maint_groups]);
    }
    
    public function createUserAction() {
        
        $data = Input::get();
        User::addNewUser($data);
        
        return redirect('users/index')->with('status', '0!');
    }
    
    

}
