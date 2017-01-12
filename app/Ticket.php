<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
                ->leftJoin('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->leftJoin('regions', 'tickets.region', '=', 'regions.region_id')
                ->leftJoin('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->leftJoin('ticket_seen', function ($join) {
                    $join->on('tickets.ticket_id', '=', 'ticket_seen.ticket_seen_ticketID')->where('ticket_seen.ticket_seen_userID', '=', Auth::user()->id);
                })
                ->where($userPlaceArray)
                ->paginate(10);

        return $tickets;
    }

    public static function getTicket($ticketID) {

        $ticket_details = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->leftJoin('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->leftJoin('regions', 'tickets.region', '=', 'regions.region_id')
                ->leftJoin('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->leftJoin('ticket_seen', function ($join) {
                    $join->on('tickets.ticket_id', '=', 'ticket_seen.ticket_seen_ticketID')->where('ticket_seen.ticket_seen_userID', '=', Auth::user()->id);
                })
                ->where('ticket_id', '=', $ticketID)
                ->first();
        return $ticket_details;
    }
    
    public static function getEmpTicket($ticketID) {

        $ticket_details = DB::table('tickets')
                ->orderBy('create_date', 'desc')
                ->leftJoin('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->leftJoin('regions', 'tickets.region', '=', 'regions.region_id')
                ->leftJoin('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
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
                ->leftJoin('buildings', 'tickets.building', '=', 'buildings.building_id')
                ->leftJoin('regions', 'tickets.region', '=', 'regions.region_id')
                ->leftJoin('tickets_types', 'tickets.type', '=', 'tickets_types.type_id')
                ->leftJoin('ticket_seen', function ($join) {
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
    
    public static function getEmpTickets($emp_username, $status){
        $statusQuery = "";
        $compare = 0;
        switch ($status) {
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
        return $tickets;
    }
    
    public static function saveNewTicket($data, $files = false){
        $emp_username = session()->get('emp_username');
        $emp_name = session()->get('emp_name');

        $date = date('Y-m-d H:i:s');
        $subject = $data["subject"];
        $description = $data["description"];
        $place = $data["place"];
        $room = $data["room"];
        $type = $data["type"];
        $emp_phone = $data["phone"];
        $ticketBuilding = $data["building"];
        $priority = $data["priority"];
        $region = $data["region_select"];

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

        $users = DB::table('users')
                ->where(function($query) {
                    $query->where('user_group', '<', 3)->orWhere('assigned_region', '=', Input::get("region_select"))->orWhere('assigned_building', '=', Input::get("building"));
                })
                ->get();
        var_dump('Emps to Send : ', $users);

        $regionRow = DB::table("regions")
                ->where('region_id', '=', $region)
                ->first();
        $buildingRow = DB::table("buildings")
                ->where('building_id', '=', $ticketBuilding)
                ->first();
        foreach ($users as $emp) {
            var_dump($emp->username);
            if ($emp->email_enabled == 1) {
                $message = '<html><body>';
                $message .= '<div style="    float: right;clear: both;direction: rtl;"><h1>طلب صيانة جديد</h1> ';
                $message .= '<p>الطلب : ' . $subject . '  </p>';
                $message .= '<p>المنشئ : ' . $emp_name . ' </p>';
                $message .= '<p>المكان : ' . $regionRow->region_name . ' - ' . $buildingRow->building_name . ' </p>';
                $message .= '<p>لفتح الطلب : ' . url('') . ' </p></div>';

                $message .= '<div style="clear: both;"><h1>New Maintenance Request</h1>';
                $message .= '<p>Request : ' . $subject . ' </p> ';
                $message .= '<p>Created By : ' . $emp_name . ' </p>';
                $message .= '<p>palce : ' . $regionRow->region_name . ' - ' . $buildingRow->building_name . ' </p>';
                $message .= '<p>Open here : ' . url('') . '</p>';
                $message .= '</body></html></div>';

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n";
                $headers .= 'From: Jouf University Maintenance <maint@maint-ju-university.com>' . " \r\n";
                mail($emp->email, '=?UTF-8?B?' . base64_encode('طلب صيانة جديد') . '?=', $message, $headers);
                
            }
        }
    }

}
