<?php
//use Symfony\Component\DomCrawler\Form;
?>
@extends('layouts.employees')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <a style="float: none;
    font-size: 20px;
    font-weight: bold;
    background-color: rgba(86, 212, 249, 0.32);
    padding: 4px;
    border-radius: 6px;" href="{{ URL::action('EmpHomeController@index', array(0)) }}">رجوع</a>
                    <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">طلب جديد</div>
                
                <div class="panel-body">
                    {{ Form::open(array('url' => 'emphome/addticketaction', 'files' => true)) }}
                    {{ Form::label('الموضوع') }}
                    {{ Form::text('subject', null,array('required', 'class'=>'form-control', 'placeholder'=>'الموضوع')) }}
                    {{ Form::label('وصف المشكلة') }}
                    {{ Form::text('description', null,array('required', 'class'=>'form-control', 'placeholder'=>'وصف المشكلة')) }}
                    {{ Form::label('نوع طلب الصيانة') }}
                    {{ Form::select('type', $types,null, array('required', 'class' => 'form-control')) }}
                    <br>
                    {{ Form::label('الأولوية') }}
                    {{ Form::select('priority', array('0' => 'منخفض', '1' => 'عادي', '2' => 'عاجل'),null, array('required', 'class' => 'form-control')) }}
                    <br>
                    {{ Form::hidden('invisible', '/ajax', array('id' => 'buildings_url')) }}
                    {{ Form::label('المنطقة') }}
                    {{ Form::select('region_select',  ['' => 'برجاء الاختيار'] + $regions, null, array('required', 'class' => 'form-control', 'id' => 'region_selects','onchange' => 'doSomething(this.value)')) }}
                    <br>
                    {{ Form::label('المبنى') }}
                    <select class="select_element form-control" id="building" name="building" required></select>
                    <br>
                    {{ Form::label('وصف المكان') }}
                    {{ Form::text('place', null,array('required', 'class'=>'form-control', 'placeholder'=>'وصف المكان')) }}
                    {{ Form::label('رقم الغرفة') }}
                    {{ Form::text('room', null,array('required', 'class'=>'form-control', 'placeholder'=>'رقم الغرفة')) }}
                    {{ Form::label('الجوال') }}
                    {{ Form::text('phone', null,array('required', 'class'=>'form-control', 'placeholder'=>'الجوال')) }}
                    {{ Form::file('files[]', array('multiple'=>true)) }}
                    {{ Form::submit('أضف الطلب', array('class'=>'btn btn-primary')) }}
                    {{ Form::close() }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
