
<?php

use Illuminate\Support\Facades\Input; ?>
@extends('layouts.app')

@section('content')

<?php
$status = "";
switch ($ticket_details->status) {
    case Config::get('constants.TICKET_STATUS_OPEN'):
        $status = "مفتوح";
        break;
    case Config::get('constants.TICKET_STATUS_RECEIVED'):
        $status = "تم الإستلام";
        break;
    case Config::get('constants.TICKET_STATUS_UNDERPROCESSING'):
        $status = "تحت التنفيذ";
        break;
    case Config::get('constants.TICKET_STATUS_REOPENED'):
        $status = "أعيد فتح الطلب";
        break;
    case Config::get('constants.TICKET_STATUS_CLOSED'):
        $status = "تم إنجاز العمل";
        break;

    default:
        break;
}
$priority = "";
switch ($ticket_details->priority) {
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
$completePlaceDesc = $ticket_details->region_name . " - " . $ticket_details->building_name;
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">تعديل الطلب : {{ $ticket_details->title }}</div>
                <div class="panel-body">
                    <div class="row">
                        <form id="" method="post" onsubmit="" action="{{ url('/home/editaction/'.$ticket_details->ticket_id) }}">

                            <div class="">
                                <div class=""><label class="title_label"> وصف المشكلة: </label>&nbsp;&nbsp;<?php echo" " . $ticket_details->description; ?></div>
                            </div>	
                            <br />
                            <div class="">
                                <div style="<?php echo ($ticket_details->priority == 2) ? "color:red;" : ""; ?>"><label class="title_label"> الأولوية: </label>&nbsp;&nbsp;{{ $priority }}</div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> الحالة: </label>&nbsp;&nbsp; {{$status}} </div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> المنشئ: </label>&nbsp;&nbsp;{{ $ticket_details->created_by_name ? $ticket_details->created_by_name : $ticket_details->created_by }}</div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> المبنى: </label>&nbsp;&nbsp;{{ $completePlaceDesc }}</div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> المكان: </label>&nbsp;&nbsp;{{ $ticket_details->place }}</div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> الغرفة: </label>&nbsp;&nbsp;{{ $ticket_details->room_number }}</div>
                            </div>
                            <br />
                            <div class="">
                                <div class=""><label class="title_label"> الجوال: </label>&nbsp;&nbsp;{{ $ticket_details->creator_phone ? $ticket_details->creator_phone : "لا يوجد" }}</div>
                            </div>
                            <br />
                            {{ csrf_field() }}
                            حالة الطلب: &nbsp;&nbsp;
                            <select id="type" name="status" class="select_element">  
                                <option value="0" <?php echo $ticket_details->status == Config::get('constants.TICKET_STATUS_OPEN') ? 'selected' : '' ?>>مفتوح</option>
                                <option value="1" <?php echo $ticket_details->status == Config::get('constants.TICKET_STATUS_RECEIVED') ? 'selected' : '' ?>>تم الإستلام</option>
                                <option value="2" <?php echo $ticket_details->status == Config::get('constants.TICKET_STATUS_UNDERPROCESSING') ? 'selected' : '' ?>>تحت التنفيذ</option>
                                <option value="4" <?php echo $ticket_details->status == Config::get('constants.TICKET_STATUS_CLOSED') ? 'selected' : ''; ?> disabled>تم إنجاز العمل</option>
                            </select>
                            <br><br>
                            رقم أمر العمل: <input id="jo_num" type="text" placeholder="WO.1366" name="jo_num" value="{{ ($ticket_details->jo_number != 0) ? $ticket_details->jo_number : "" }}" />
                            <input name="staff_username" type="hidden" value="{{ $username }}">
                            <input name="ticketID" type="hidden" value="{{ $ticket_details->ticket_id }}" />
                            <br><br>
                            <input  type="submit" value="حفظ"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
