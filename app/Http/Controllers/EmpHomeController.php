<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use App\Ticket;

class EmpHomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    public function index($segment) {
        $emp_username = session()->get('emp_username');
        $emp_name = session()->get('emp_name');
        $statusQuery = "";
        $compare = 0;
        switch ($segment) {
            case 0:
                $statusQuery = '>=';
                $compare = 0;
                break;
            case 1:
                $statusQuery = '<';
                $compare = 4;
                break;
            case 2:
                $statusQuery = '=';
                $compare = 4;
                break;

            default:
                break;
        }
        $tickets = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->join('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->join('regions', 'tickets.region', '=', 'regions.region_id')
                ->join('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->where([
                        ['created_by', '=', $emp_username],
                        ['status', $statusQuery, $compare]
                ])
                ->paginate(10);
        return view('employees.emphome', ['tickets' => $tickets, 'username' => $emp_username, 'name' => $emp_name]);
    }

    public function auth($wsUsername, $wsPassword, $emp_username, $emp_name, $date) {
        $ticket_details = DB::table('ws_auth')
                ->where('id', '=', '1')
                ->first();
        $current_date = date('Y-m-d');
        if ($current_date == $date) {
            if (($wsUsername == $ticket_details->username) && ($wsPassword == $ticket_details->password)) {
                session(['emp_logged_in' => TRUE]);
                session(['last_activity' => time()]);
                session(['expire_time' => 15 * 60 * 10]);
                session(['emp_username' => $emp_username]);
                session(['emp_name' => $emp_name]);
                session(['loggedin_time' => time()]);
                return redirect()->action('EmpHomeController@index', array($emp_username, $emp_name, 1));
            }
        }
    }

    public function addTicket() {
        $maint_types = array();
        $maint_regions = array();

        $types = DB::table('tickets_types')
                ->select('type_id', 'type_name')
                ->get();
        foreach ($types as $type) {
            $maint_types[$type->type_id] = $type->type_name;
        }

        $regions = DB::table('regions')
                ->get();
        foreach ($regions as $region) {
            $maint_regions[$region->region_id] = $region->region_name;
        }

        $buildings = DB::table('buildings')
                ->get();
        return view('employees.empAddTicket', ['types' => $maint_types, 'regions' => $maint_regions, 'buildings' => $buildings]);
    }

    public function addTicketAction(Request $request) {
        $files = Input::file('files');

        $emp_username = session()->get('emp_username');
        $emp_name = session()->get('emp_name');
//        var_dump($emp_username, $emp_name);die();
        $date = date('Y-m-d H:i:s');
        $subject = Input::get("subject");
        $description = Input::get("description");
        $place = Input::get("place");
        $room = Input::get("room");
        $type = Input::get("type");
        $emp_phone = Input::get("phone");
        $ticketBuilding = Input::get("building");
        $priority = Input::get("priority");
        $region = Input::get("region_select");

        $lastid = DB::table('tickets')->insertGetId([
            'title' => $subject,
            'description' => $description,
            'region' => $region ? $region : 0,
            'building' => $ticketBuilding,
            'users_group' => 0,
            'type' => $type,
            'priority' => $priority,
            'create_date' => $date,
            'created_by' => $emp_username,
            'created_by_name' => $emp_name,
            'close_date' => $date,
            'jo_number' => 0,
            'reopen_comment' => 0,
            'close_comment' => 0,
            'opened' => 0,
            'room_number' => $room,
            'place' => $place,
            'creator_phone' => $emp_phone,
            'status' => \Config::get('constants.TICKET_STATUS_OPEN'),
                ]
        );

        DB::table('tickets_log')->insert([
                [
                'ticket_id' => $lastid,
                'action_date' => $date,
                'status_from' => 0,
                'status_to' => \Config::get('constants.TICKET_STATUS_OPEN'),
                'changed_by' => $emp_username,
            ]
        ]);

//        $staff_emps = $wpdb->get_results("SELECT  * FROM mju_staff_users WHERE (assigned_region = '0') OR (assigned_region = '" . $region . "' AND assigned_building = '0')  OR (assigned_region = '" . $region . "' AND assigned_building = '" . $ticketBuilding . "')");

        $staff_emps = DB::table('users')
                ->where('assigned_region', '=', 0)
                ->orWhere('assigned_region', '=', $region)
                ->orWhere('assigned_building', '=', $ticketBuilding)
//                ->orWhere(['assigned_region', '=', $region], ['assigned_building', '=', assigned_building])
                ->get();

        foreach ($staff_emps as $emp) {
            DB::table('ticket_seen')->insert([
                    [
                    'ticket_seen_id' => 0,
                    'ticket_seen_ticketID' => $lastid,
                    'ticket_seen_userID' => $emp->id,
                    'ticket_seen_opened' => 0,
                ]
            ]);
        }
        var_dump($files);
        //Uploads
        if (count($files) > 0) {
            foreach ($files as $file) {
                var_dump($file);
                $destinationPath = public_path() . '/storage/uploads/' . $emp_username . '/' . $lastid . '/';
                // GET THE FILE EXTENSION
                $extension = $file->getClientOriginalExtension();
                $original_name = $file->getClientOriginalName();
                // RENAME THE UPLOAD WITH RANDOM NUMBER
                $fileName = $original_name . rand(11111, 99999) . '.' . $extension;
                // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
                $upload_success = $file->move($destinationPath, $fileName);
            }
        }

//        die();
        return redirect()->action('EmpHomeController@index', array($emp_username, $emp_name, 1));
    }
    
    public function ticketDetails($ticketID = FALSE) {
        //Make ticket seen for the logged in user

        $ticket_details = Ticket::getEmpTicket($ticketID);
        
        $ticket_log = DB::table('tickets_log')
                ->where('ticket_id', '=', $ticketID)
                ->get();

        return view('employees.empTicketDetails', ['ticket_details' => $ticket_details, 'ticket_log' => $ticket_log]);
    }

}
