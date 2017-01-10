
<?php

use Illuminate\Support\Facades\Input; ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!--<div class="col-xs-12 ">-->
        <div class="panel panel-default">
            <div class="panel-heading">الطلبات</div>
            <div class="no-print" id="segment" >

                <a href="<?php echo URL::action('HomeController@index', 0); ?>" title="كل الطلبات">الكل</a> | 
                <a href="<?php echo URL::action('HomeController@index', 1); ?>" title="طلبات مفتوحة">طلبات مفتوحة</a> |
                <a href="<?php echo URL::action('HomeController@index', 2); ?>" title="طلبات مستلمة">طلبات مستلمة</a> | 
                <a href="<?php echo URL::action('HomeController@index', 3); ?>" title="طلبات تحت التنفيذ">طلبات تحت التنفيذ</a> | 
                <a href="<?php echo URL::action('HomeController@index', 5); ?>" title="طلبات مغلقة">طلبات مغلقة</a>

                <form id="multiple_action_container">
                    <fieldset id="multiple_form_fieldset" >
                        {{ csrf_field() }}
                        <input id="submitURL" name="submitURL" type="hidden" value="{{ url('/home/editmultipleaction/') }}">
                        <input id="ismultiple" name="ismultiple" type="hidden" value="1">
                        <input id="selectedTickets" name="selectedTickets" type="hidden" value="1">
                        <select id="selected_tickets_select" name="selected_tickets_select">
                            <option value="<?php echo Config::get('constants.TICKET_STATUS_OPEN'); ?>">مفتوح</option>
                            <option value="<?php echo Config::get('constants.TICKET_STATUS_RECEIVED'); ?>">تم الإستلام</option>
                            <option value="<?php echo Config::get('constants.TICKET_STATUS_UNDERPROCESSING'); ?>">تحت التنفيذ</option>
                            <option value="<?php echo Config::get('constants.TICKET_STATUS_CLOSED'); ?>">إغلاق</option>
                            <option value="77">حذف</option>
                        </select>
                        <button  type="submit">تطبيق</button>
                    </fieldset>
                </form>
            </div>
            <div class="panel-body">
                <div class="row">
                    <table class="tickets_table">
                        <thead>
                            <tr>
                                <th style="width: 2%;"><input class="staff_ticket_checkmark_all square_input" id="select_all_tickets" type="checkbox" name="all_tickets_checked" value="all"></th>
                                <th class="mobile_hidden" style="width: 2%;"><span class="glyphicon glyphicon-eye-open"></span></th>
                                <th class="mobile_hidden" style="width: 7%;">تاريخ الفتح</th>
                                <th style="width: 15%;">الوصف</th>
                                <th class="mobile_hidden" style="width: 10%;">المنشئ</th>
                                <th class="mobile_hidden" style="width: 3%;">الأولوية</th>
                                <th class="mobile_hidden" style="width: 13%;">المكان</th>
                                <th class="mobile_hidden" style="width: 6.5%;">النوع</th>

                                <th class="mobile_hidden" style="width: 5%;">أمر العمل</th>
                                <th style="width: 5%;">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($tickets as $ticket) {
                                $files = glob( public_path() . '/storage/uploads/' . $ticket->created_by_name . '/' . $ticket->ticket_id . '/*');
                                $attachIcon = "";
                                if (count($files) > 0) {
                                    $attachIcon = "<span class='glyphicon glyphicon-paperclip'></span>";
                                }
                                $trClass = "";
                                switch ($ticket->status) {
                                    case Config::get('constants.TICKET_STATUS_OPEN'):
                                        $ticketCurrentStatus = "مفتوح";
                                        break;
                                    case Config::get('constants.TICKET_STATUS_RECEIVED'):
                                        $ticketCurrentStatus = "مستلم";
                                        break;
                                    case Config::get('constants.TICKET_STATUS_UNDERPROCESSING'):
                                        $ticketCurrentStatus = "تحت التنفيذ";
                                        break;
                                    case Config::get('constants.TICKET_STATUS_REOPENED'):
                                        $ticketCurrentStatus = "أعيد فتحه";
                                        $trClass = "reopen_ticker_tr";
                                        break;
                                    case Config::get('constants.TICKET_STATUS_CLOSED'):
                                        $ticketCurrentStatus = "تم إنجاز العمل";
                                        break;
                                    default:
                                        break;
                                }

                                $priority = "";
                                switch ($ticket->priority) {
                                    case Config::get('constants.TICKET_PRIORITY_LOW'):
                                        $priority = "منخفض";
                                        break;
                                    case Config::get('constants.TICKET_PRIORITY_NORMAL'):
                                        $priority = "عادي";
                                        break;
                                    case Config::get('constants.TICKET_PRIORITY_HIGH'):
                                        $priority = "عاجل";
                                        break;

                                    default:
                                        break;
                                }
                                $completePlaceDesc = $ticket->region_name . " - " . $ticket->building_name;

                                $current_title = $ticket->title;
                                if (mb_strlen($current_title) > 35) {
                                    $current_title = mb_substr($current_title, 0, 35, "UTF-8");
                                    $current_title = substr($current_title, 0, strrpos($current_title, ' ')) . ' ...';
                                }
                                ?>
                                <tr  data-ticketid="<?php echo $ticket->ticket_id; ?>" class="clickable_tr" data-href="{{ Url::action('HomeController@ticketDetails', ['data' => $ticket->ticket_id]) }}">
                                    <td class=""><input class="staff_ticket_checkmark square_input" id="<?php echo $ticket->ticket_id; ?>" type="checkbox" name="ticket_checked" value="<?php echo $ticket->ticket_id; ?>"></td>
                                    <td class="mobile_hidden"><?php echo ($ticket->ticket_seen_opened == 1) ? "<span class='glyphicon glyphicon-ok-circle'>" : "<span class='glyphicon glyphicon-bullhorn new_ticket_label'></span>"; ?></td>
                                    <td class="mobile_hidden"><?php echo date("Y-m-d", strtotime($ticket->create_date)); ?></a></td>
                                    <td class="description_td"><?php echo $current_title; ?></td>
                                    <td class="mobile_hidden"><?php echo $ticket->created_by_name ? $ticket->created_by_name : $ticket->created_by; ?></td>
                                    <td class="mobile_hidden" style="<?php echo ($ticket->priority == 2) ? 'color:red' : ''; ?>"><?php echo $priority; ?></td>
                                    <td class="mobile_hidden"><?php echo $completePlaceDesc; ?></a></td>
                                    <td class="mobile_hidden"><?php echo $ticket->type_name; //$ticket_type[0]->name;                 ?></td>

                                    <td class="mobile_hidden"><?php echo $ticket->jo_number ? $ticket->jo_number : "-"; ?></td>
                                    <td><?php
                                        echo $ticketCurrentStatus;
                                     echo " " . $attachIcon; 
                                        ?></td>
                                </tr>


                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
<?php echo $tickets->appends(Input::except('page'))->render(); ?>
                </div>
            </div>
        </div>
        <!--</div>-->
    </div>
</div>
@endsection
