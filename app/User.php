<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getUsers() {
        $users = DB::table('users')
                ->orderBy('user_group', 'asc')
                ->leftJoin('buildings', 'users.assigned_building', '=', 'buildings.building_id')
                ->leftJoin('regions', 'users.assigned_region', '=', 'regions.region_id')
                ->leftJoin('users_groups', 'users.user_group', '=', 'users_groups.group_id')
                ->get();
        return $users;
    }

    public static function addNewUser(array $data) {
        DB::table('users')->insert([
                [
                'username' => $data['username'],
                'email' => $data['email'],
                'name' => $data['name'],
                'user_group' => $data['groups'],
                'assigned_region' => $data['region'],
                'assigned_building' => $data['building'],
                'password' => bcrypt($data['password']),
            ]
        ]);
    }
    public static function editUserUser(array $data) {
        DB::table('users')
                ->where('id', '=', $data['user_id'])
                ->update(['username' => $data['username'], 'name' => $data['name'], 'user_group' => $data['groups'], 'assigned_region' => $data['region'] ? $data['region'] : 0, 'assigned_building' => $data['building'] ? $data['building'] : 0]);
    }
    
    public static function saveUserSettings(array $data){
        DB::table('users')
                ->where('id', '=', $data['user_id'])
                ->update(['email' => $data['email'], 'email_enabled' => $data['email_enabled']]);
    }

        public static function getUser($userID) {
        $user = DB::table("users")
                ->leftJoin('buildings', 'users.assigned_building', '=', 'buildings.building_id')
                ->leftJoin('regions', 'users.assigned_region', '=', 'regions.region_id')
                ->leftJoin('users_groups', 'users.user_group', '=', 'users_groups.group_id')
                ->where('id', '=', $userID)
                ->first();
        
        return $user;
    }

}
