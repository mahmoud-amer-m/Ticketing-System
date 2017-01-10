<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class Ticket extends Model {

    //
    protected $table = 'tickets';

    public static function getTickets($status = FALSE) {

        $searchStatus = 0;
        if ($status != 0)
            $searchStatus = $status - 1;

        $user_group = Auth::user()->user_group;
        $userPlaceArray = FALSE;
        switch ($user_group) {
            case 1:
                $userPlaceArray = [['status', ($status == 0) ? '>=' : '=', $searchStatus]];
                break;
            case 2:
                $userPlaceArray = [['status', ($status == 0) ? '>=' : '=', $searchStatus]];
                break;
            case 3:
                $userPlaceArray = [['status', ($status == 0) ? '>=' : '=', $searchStatus], ['region', '=', Auth::user()->assigned_region]];
                break;
            case 4:
                $userPlaceArray = [['status', ($status == 0) ? '>=' : '=', $searchStatus], ['building', '=', Auth::user()->assigned_building]];
                break;

            default:
                break;
        }
        $tickets = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->join('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->join('regions', 'tickets.region', '=', 'regions.region_id')
                ->join('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->join('ticket_seen', function ($join) {
                    $join->on('tickets.ticket_id', '=', 'ticket_seen.ticket_seen_ticketID')->where('ticket_seen.ticket_seen_userID', '=', Auth::user()->id);
                })
                ->where($userPlaceArray)
                ->paginate(10);

        return $tickets;
    }

    public static function getTicket($ticketID) {

        $ticket_details = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->join('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->join('regions', 'tickets.region', '=', 'regions.region_id')
                ->join('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->join('ticket_seen', function ($join) {
                    $join->on('tickets.ticket_id', '=', 'ticket_seen.ticket_seen_ticketID')->where('ticket_seen.ticket_seen_userID', '=', Auth::user()->id);
                })
                ->where('ticket_id', '=', $ticketID)
                ->first();
        return $ticket_details;
    }
    
    public static function getEmpTicket($ticketID) {

        $ticket_details = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->join('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->join('regions', 'tickets.region', '=', 'regions.region_id')
                ->join('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->where('ticket_id', '=', $ticketID)
                ->first();
        return $ticket_details;
    }
    
    public static function editTicket($ticketID, $status, $jo) {
        $ticket_details = DB::table('tickets')
                ->where('ticket_id', '=', $ticketID)
                ->first();
        $oldStatus = $ticket_details->status;
        $update_ticket = DB::table('tickets')
                ->where('ticket_id', '=', $ticketID)
                ->update(['status' => $status, 'jo_number' => $jo ? $jo : 0]);

        $date = date('Y-m-d H:i:s');
        $ticket_details = DB::table('tickets')
                ->join('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->join('regions', 'tickets.region', '=', 'regions.region_id')
                ->join('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->join('ticket_seen', function ($join) {
                    $id = Auth::id();
                    $join->on('tickets.ticket_id', '=', 'ticket_seen.ticket_seen_ticketID')->where('ticket_seen.ticket_seen_userID', '=', Auth::user()->id);
                })
                ->where('ticket_id', '=', $ticketID)
                ->first();

        DB::table('tickets_log')->insert([
                [
                'ticket_id' => $ticketID,
                'action_date' => $date,
                'status_from' => $oldStatus,
                'status_to' => $status,
                'changed_by' => Auth::user()->username,
            ]
        ]);
        
        return $ticket_details;
    }
    public static function closeTicket($ticketID, $comment) {
        $ticket_details = DB::table('tickets')
                ->where('ticket_id', '=', $ticketID)
                ->first();
        $oldStatus = $ticket_details->status;
        $update_ticket = DB::table('tickets')
                ->where('ticket_id', '=', $ticketID)
                ->update(['status' => \Config::get('constants.TICKET_STATUS_CLOSED'), 'close_comment' => $comment ? $comment : '']);

        $date = date('Y-m-d H:i:s');

        DB::table('tickets_log')->insert([
                [
                'ticket_id' => $ticketID,
                'action_date' => $date,
                'status_from' => $oldStatus,
                'status_to' => \Config::get('constants.TICKET_STATUS_CLOSED'),
                'changed_by' => Auth::user()->username,
            ]
        ]);
        
    }
    public static function deleteTicket($ticketID) {
        DB::table('tickets')->where('ticket_id', '=', $ticketID)->delete();
        
    }
    
    public static function setTicketSeenForUser ($ticketID){
         $update_ticket_seen = DB::table('ticket_seen')
                ->where([
                        ['ticket_seen_userID', '=', Auth::user()->id],
                        ['ticket_seen_ticketID', '=', $ticketID]
                ])
                ->update(['ticket_seen_opened' => '1']); 
    }

}
