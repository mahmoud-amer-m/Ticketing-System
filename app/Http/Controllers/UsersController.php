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
    
    public function editUser($userID) {
        
        $user = User::getUser($userID);

        
        $maint_buildings = $maint_regions = $maint_groups = $maint_region_buildings = array();
        

        $regions = DB::table('regions')
                ->get();

        foreach ($regions as $region) {
            $maint_regions[$region->region_id] = $region->region_name;
        }
        $regionBuildings = DB::table('buildings')
                ->where('building_region', '=', $user->assigned_region)
                ->get();
        foreach ($regionBuildings as $building) {
            $maint_region_buildings[$building->building_id] = $building->building_name;
        }
        
        $buildings= DB::table('buildings')
                ->where('building_id', '=', $user->assigned_building)
                ->get();
        
        foreach ($buildings as $building) {
            $maint_buildings[$building->building_id] = $building->building_name;
        }
        $groups = DB::table('users_groups')
                ->get();
        foreach ($groups as $group) {
            $maint_groups[$group->group_id] = $group->group_name;
        }

        return view('staff.editUser', ['regions' => $maint_regions, 'buildings' => $maint_buildings, 'regionBuildings' => $maint_region_buildings, 'groups' => $maint_groups, 'user' => $user]);
    }
    
    public function userSettings() {
        $user = User::getUser(Auth::user()->id);
        return view('staff.userSettings', ['user' => $user]);
    }
    
    public function editsettingsaction() {
        $data = Input::get();
        User::saveUserSettings($data);
        
        return redirect('users/settings');
    }

    public function createUserAction() {
        
        $data = Input::get();
        User::addNewUser($data);
        
        return redirect('users/index')->with('status', '0!');
    }
    
    public function editUserAction() {
        
        $data = Input::get();
        User::editUserUser($data);
        
        return redirect('users/index')->with('status', '0!');
    }
    
    

}
