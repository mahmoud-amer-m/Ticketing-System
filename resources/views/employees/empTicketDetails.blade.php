@extends('layouts.employees')

@section('content')

<?php $files = glob( public_path() . '/storage/uploads/' . $ticket_details->created_by_name . '/' . $ticket_details->ticket_id . '/*') ?>

<div class="container">
    
    <div class="row">
        <!--<div class="col-xs-12 col-md-12">-->
        
            <div class="panel panel-default">
                
                <?php
                $completePlaceDesc = $ticket_details->region_name . " - " . $ticket_details->building_name;
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
                ?>
                
                    <a style="float: none;
    font-size: 20px;
    font-weight: bold;
    background-color: rgba(86, 212, 249, 0.32);
    padding: 4px;
    border-radius: 6px;" href="{{ URL::action('EmpHomeController@index', array(0)) }}">رجوع</a>
                    <br><br>
                    <div class="ticket_details">
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> الموضوع </label>&nbsp;&nbsp;<?php echo" " . $ticket_details->title; ?></p>
                        <p class="ticket_details_p right_paragraph" id="description_label"><label class="title_label"> وصف المشكلة </label>&nbsp;&nbsp;<?php echo" " . $ticket_details->description; ?></p>
                        <p class="ticket_details_p right_paragraph" style="<?php echo ($ticket_details->priority == Config::get('constants.TICKET_PRIORITY_HIGH')) ? "color:red;" : ""; ?>"><label class="title_label"> الأولوية </label>&nbsp;&nbsp;<?php echo" " . $priority; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> الحالة </label>&nbsp;&nbsp;<?php echo $status; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> النوع </label>&nbsp;&nbsp;<?php echo $ticket_details->type_name; ?></p>
                        <p class="ticket_details_p left_paragraph"><label class="title_label"> التعليق </label>&nbsp;&nbsp;<?php echo $ticket_details->close_comment; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> تاريخ الإنشاء </label>&nbsp;&nbsp;<?php echo date("Y-m-d", strtotime($ticket_details->create_date)); ?></p>
                        <p class="ticket_details_p left_paragraph"><label class="title_label"> المنشئ </label>&nbsp;&nbsp;<?php echo $ticket_details->created_by_name ? $ticket_details->created_by_name : $ticket_details->created_by; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> رقم أمر العمل </label>&nbsp;&nbsp;<?php echo $ticket_details->jo_number ? $ticket_details->jo_number : "لا يوجد"; ?></p>
                        <p class="ticket_details_p left_paragraph"><label class="title_label"> المبنى </label>&nbsp;&nbsp;<?php echo $completePlaceDesc; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> المكان </label>&nbsp;&nbsp;<?php echo $ticket_details->place; ?></p>

                        <p class="ticket_details_p left_paragraph"><label class="title_label"> الجوال </label>&nbsp;&nbsp;<?php echo $ticket_details->creator_phone ? $ticket_details->creator_phone : "لا يوجد"; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> الغرفة </label>&nbsp;&nbsp;<?php echo $ticket_details->room_number; ?></p>
                        <p class="ticket_details_p right_paragraph"><label class="title_label"> المرفقات </label>&nbsp;&nbsp; <?php // echo (count($files) > 0) ? "عدد " . count($files) : "لا يوجد" ?></p>
                        <p class="ticket_details_p left_paragraph">
                            <?php
                            if (count($files) > 0) {
                                echo "<div id='t_details_attach_container' class='no-print'>";
                                foreach ($files as $file) { // iterate files
                                    if (is_file($file))
                                        $type = (pathinfo(public_path() . '/storage/uploads/' . $ticket_details->created_by_name . '/' . $ticket_details->ticket_id . '/' . basename($file)));
//                                        $contents = File::get(() . '/storage/uploads/' . $ticket_details->created_by_name . '/' . $ticket_details->ticket_id . '/' . basename($file));
//                                        echo '<a href="/storage/uploads/' . $ticket_details->created_by_name . '/' . $ticket_details->ticket_id . '/' . basename($file).'">'.basename($file).'</a>';
                                    echo "<div class='uploaded_file_result_container'><span class='glyphicon glyphicon-paperclip'></span><a target='_blank' class='uploaded_file_result_link attach_view fancybox' data-type='" . $type["extension"] . "' href='/storage/uploads/" . $ticket_details->created_by_name . "/" . $ticket_details->ticket_id . "/" . basename($file)."'>".basename($file) . "</a></div>";
                                }
                                echo "</div><br />";
                            }
                            ?>
                        </p>
                        <br />
                        <p class="ticket_details_p right_paragraph no-print ticket_loop">
                            <label class="title_label"> دورة الطلب </label>&nbsp;&nbsp; <br>

                            <?php
                            $i = 0;
                            foreach ($ticket_log as $record) {
//                                var_dump($record);
//                                continue;
                                $space = "";
                                if ($i != 0)
                                    $space = "<br />";
                                if (($record->status_from == 0) && ($record->status_to == 0))
                                    echo $space . '*' . 'تم فتح الطلب بواسطة : ' . $record->changed_by . '&nbsp;&nbsp; بتاريخ : ' . date("Y-m-d", strtotime($record->action_date));
                                else if (($record->status_from == 4) && ($record->status_to == 3))
                                    echo $space . '*' . 'تم إعادة فتح الطلب بواسطة : ' . $record->changed_by . '&nbsp;&nbsp; بتاريخ : ' . date("Y-m-d", strtotime($record->action_date));
                                else if ($record->status_to == 4)
                                    echo $space . '*' . 'تم إغلاق الطلب بواسطة عضو بإدارة التشغيل و الصيانة' . '&nbsp;&nbsp; بتاريخ : ' . date("Y-m-d", strtotime($record->action_date));
                                else if ($record->status_to == 1)
                                    echo $space . '*' . 'تم إستلام بواسطة عضو بإدارة التشغيل و الصيانة' . '&nbsp;&nbsp; بتاريخ : ' . date("Y-m-d", strtotime($record->action_date));
                                else if ($record->status_to == 2)
                                    echo $space . '*' . 'الطلب تحت التنفيذ -  بواسطة عضو بإدارة التشغيل و الصيانة' . '&nbsp;&nbsp; بتاريخ : ' . date("Y-m-d", strtotime($record->action_date));
                                $i ++;
                            }
                            ?>
                        </p>

                </div>
            <!--</div>-->
        </div>
    </div>
</div>
@endsection