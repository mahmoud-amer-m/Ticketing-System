<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class StaffTicketDetails extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ticketID = FALSE)
    {
        $id = Auth::id();
        $ticket_details = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->join('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->join('regions', 'tickets.region', '=', 'regions.region_id')
                ->join('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->join('ticket_seen', function ($join) {
                    $id = Auth::id();
                    $join->on('tickets.ticket_id', '=', 'ticket_seen.ticket_seen_ticketID')->where('ticket_seen.ticket_seen_userID', '=', $id);
                })
                ->where('ticket_id', '=', $ticketID)
                ->first();
                
        $ticket_log = DB::table('tickets_log')
                ->where('ticket_id', '=', $ticketID)
                ->get();

        return view('staff.ticket', ['ticket_details' => $ticket_details, 'ticket_log' => $ticket_log]);
    }
    
    
}
