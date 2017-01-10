<?php
//use Symfony\Component\DomCrawler\Form;
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">طلب جديد</div>
                <div class="panel-body">
                    {{ Form::open(array('url' => 'users/adduseraction')) }}
                    {{ Form::label('الإسم') }}
                    {{ Form::text('name', null,array('required', 'class'=>'form-control', 'placeholder'=>'الإسم')) }}
                    {{ Form::label('إسم المستخدم') }}
                    {{ Form::text('username', null,array('required', 'class'=>'form-control', 'placeholder'=>'إسم المستخدم')) }}
                    {{ Form::label('الإيميل') }}
                    {{ Form::text('email', null,array('required', 'class'=>'form-control', 'placeholder'=>'الإيميل')) }}
                    {{ Form::label('كلمة المرور') }}
                    {{ Form::text('password', null,array('required', 'class'=>'form-control', 'type' => 'password')) }}
                    {{ Form::label('الصلاحيات') }}
                    {{ Form::select('groups',  ['' => 'برجاء الاختيار'] + $groups,null, array('required', 'class' => 'form-control')) }}
                    <br>
                    {{ Form::label('المنطقة') }}
                    {{ Form::select('region', ['' => 'برجاء الاختيار'] + $regions,null, array('required', 'class' => 'form-control', 'id' => 'region_selects','onchange' => 'doSomething(this.value)')) }}
                    <br>
                    {{ Form::hidden('invisible', '/ajax', array('required', 'id' => 'buildings_url')) }}
                    {{ Form::label('المبنى') }}
                    <select class="select_element form-control" id="building" name="building" required></select>
                    <br>
                    
                    {{ Form::submit('أضف الطلب', array('class'=>'btn btn-primary')) }}
                    {{ Form::close() }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
