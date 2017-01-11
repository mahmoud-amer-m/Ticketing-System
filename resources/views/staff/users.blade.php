<?php

use Illuminate\Support\Facades\Input; ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!--<div class="col-xs-12 ">-->
        <div class="panel panel-default">
            <div class="no-print" id="segment" >
                <a href="<?php echo URL::action('UsersController@create'); ?>" title="إضافة مستخدم">إضافة مستخدم</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <table class="tickets_table">
                        <thead>
                            <tr style="font-size: 17px;">
                                <th style="width: 2%;">الرقم</th>
                                <th style="width: 15%;">إسم المستخدم</th>
                                <th  style="width: 10%;">الصلاحيات</th>
                                <th  style="width: 10%;">المكان</th>
                                <th  style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($users as $staff_emp) {
                                $usrRgn = $staff_emp->region_name;
                                $usrBldg = $staff_emp->building_name;

                                $rgn = $usrRgn ? $usrRgn : "";
                                $bldg = $usrBldg ? " - " . $usrBldg : "";
                                
                                ?>
                                <tr class="">

                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $staff_emp->name; ?></td>
                                    <td><?php echo $staff_emp->group_name ?></td>
                                    <td><?php echo $rgn . ' ' . $bldg ?></td>
                                    <td><a href="{{ URL::action('UsersController@editUser', $staff_emp->id) }}">تعديل</a></td>
                                </tr>


                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!--</div>-->
    </div>
</div>
@endsection