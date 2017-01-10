<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Ticket;

class HomeController extends Controller {

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
        $tickets = Ticket::getTickets($status);

        return view('staff.home', ['tickets' => $tickets]);
    }

    public function edit($ticketID = FALSE) {
        //Make ticket seen for the logged in user
        $update_ticket_seen = Ticket::setTicketSeenForUser($ticketID);
        $ticket_details = Ticket::getTicket($ticketID);
        return view('staff.editTicket', ['ticket_details' => $ticket_details, 'username' => Auth::user()->username]);
    }

    public function editaction($ticketID = FALSE) {

        $ticket_details = Ticket::editTicket($ticketID, Input::get('status'), Input::get('jo_num'));

        return view('staff.editTicket', ['ticket_details' => $ticket_details, 'username' => Auth::user()->username]);
    }

    public function editmultipleaction() {
        
        $tickets = Input::get('selectedTickets');
        $newStatus = Input::get("selected_tickets_select");
        $selectedTicketsArr = explode(",", $tickets);

        if (!($newStatus == 77)) {
            foreach ($selectedTicketsArr as $id) {
                $update_ticket_seen = Ticket::setTicketSeenForUser($id);
                $ticket_details = Ticket::editTicket($id, $newStatus, 0);
            }
        } else {
            foreach ($selectedTicketsArr as $value) {
                Ticket::deleteTicket($value, '');
            }
        }
        
        return redirect('home/index/0')->with('status', '0!');
    }

    public function ticketDetails($ticketID = FALSE) {
        //Make ticket seen for the logged in user
        $id = Auth::id();
        $update_ticket_seen = Ticket::setTicketSeenForUser($ticketID);


        $ticket_details = Ticket::getTicket($ticketID);
        
        $ticket_log = DB::table('tickets_log')
                ->where('ticket_id', '=', $ticketID)
                ->get();

        return view('staff.ticket', ['ticket_details' => $ticket_details, 'ticket_log' => $ticket_log]);
    }

    public function closeTicket($ticketID = false) {

        $id = Auth::id();
        $update_ticket_seen = Ticket::setTicketSeenForUser($ticketID);

        $ticket_details = Ticket::getTicket($ticketID);

        return view('staff.closeTicket', ['ticket_details' => $ticket_details, 'username' => Auth::user()->username]);
    }

    public function closeTicketAction($ticketID = false) {

        Ticket::closeTicket($ticketID, Input::get('comment'));

        $ticket_details = Ticket::getTicket($ticketID);

        return view('staff.closeTicket', ['ticket_details' => $ticket_details, 'username' => Auth::user()->username]);
    }

}
