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
        
        $tickets = Ticket::getEmpTickets($emp_username, $segment);
//        var_dump($tickets);die();
        
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
        $data = Input::get();
        Ticket::saveNewTicket($data, $files);
        $emp_username = session()->get('emp_username');
        $emp_name = session()->get('emp_name');

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
