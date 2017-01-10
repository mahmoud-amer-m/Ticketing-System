
<?php

use Illuminate\Support\Facades\Input; ?>
@extends('layouts.employees')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">الطلبات</div>
                <div class="no-print" id="segment" >

                    <a href="<?php echo URL::action('EmpHomeController@index', array(0)); ?>" title="كل الطلبات">الكل</a> | 
                    <a href="<?php echo URL::action('EmpHomeController@index', array(1)); ?>" title="طلبات مفتوحة">طلبات مفتوحة</a> |
                    <a href="<?php echo URL::action('EmpHomeController@index', array(2)); ?>" title="طلبات مغلقة">طلبات مغلقة</a> | 
                    <a href="<?php echo URL::action('EmpHomeController@addTicket'); ?>" title="طلبات تحت التنفيذ">تقديم طلب جديد</a>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <table class="tickets_table" id="one_employee_manpower_print">
                            <thead>
                                <tr style="background-color:rgb(189, 186, 167);">
                                    <th style="width: 15%;">الوصف</th>
                                    <th class="mobile_hidden"  style="width: 12%;">المكان</th>
                                    <th class="mobile_hidden" style="width: 6.5%;">النوع</th>
                                    <th class="mobile_hidden"  style="width: 5%;">تاريخ فتح الطلب</th>
                                    <th class="mobile_hidden"  style="width: 5%;">أمر العمل</th>
                                    <th style="width: 5%;">الحالة</th>
                                    <th class="mobile_hidden" style="width: 3%;">الأولوية</th>
                                    <th class="mobile_hidden" style="width: 10%;">التعليق</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($tickets as $emp_ticket) {
                                    $trClass = "";
                                    switch ($emp_ticket->status) {
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
                                    switch ($emp_ticket->priority) {
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
                                    $completePlaceDesc = $emp_ticket->region_name . " - " . $emp_ticket->building_name;

                                    $close_comment = ($emp_ticket->close_comment) ? $emp_ticket->close_comment : "-";
                                    if (strlen($close_comment) > 60) {
                                        $close_comment = mb_substr($close_comment, 0, 35, "UTF-8");
                                        $close_comment = substr($close_comment, 0, strrpos($close_comment, ' ')) . ' ...';
                                    }

                                    $current_title = $emp_ticket->title;
                                    if (mb_strlen($current_title) > 44) {
                                        $current_title = mb_substr($current_title, 0, 44, "UTF-8");
                                        $current_title = substr($current_title, 0, strrpos($current_title, ' ')) . ' ...';
                                    }
//                                    $files = glob(get_template_directory() . "-child/tickets-files/" . $emp_ticket->ticket_id . '/*');
//                                    $attachIcon = "";
//                                    if (count($files) > 0) {
//                                        $attachIcon = "<span class='glyphicon glyphicon-paperclip'></span>";
//                                    }
                                    ?>
                                    <tr data-ticketid="<?php echo $emp_ticket->ticket_id; ?>" class="clickable_tr <?php echo $trClass; ?>" data-href="{{ Url::action('EmpHomeController@ticketDetails', ['data' => $emp_ticket->ticket_id]) }}">

                                        <td class="description_td"><?php echo $current_title; ?></td>
                                        <td class="mobile_hidden"><?php echo $completePlaceDesc; ?></td>
                                        <td class="mobile_hidden"><?php echo $emp_ticket->type_name; ?></td>
                                        <td class="mobile_hidden"><?php echo date("Y-m-d", strtotime($emp_ticket->create_date)); ?></td>
                                        <td class="mobile_hidden"><?php echo $emp_ticket->jo_number ? $emp_ticket->jo_number : "-"; ?></td>
                                        <td><?php
                                            echo $ticketCurrentStatus;
//                                    echo " " . $attachIcon; 
                                            ?></td>
                                        <td class="mobile_hidden" style="<?php echo ($emp_ticket->priority == Config::get('constants.TICKET_PRIORITY_HIGH')) ? 'color:red;' : ''; ?>"><?php echo $priority; ?></td>
                                        <td class="mobile_hidden"><?php echo $close_comment; ?></td>
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
        </div>
    </div>
</div>
@endsection
