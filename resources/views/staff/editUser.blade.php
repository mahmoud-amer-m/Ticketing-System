<?php
//use Symfony\Component\DomCrawler\Form;
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">مستخدم جديد</div>
                <div class="panel-body">
                    {{ Form::open(array('url' => 'users/edituseraction')) }}
                    {{ Form::label('الإسم') }}
                    {{ Form::text('name', $user->name,array('required', 'class'=>'form-control', 'placeholder'=>'الإسم')) }}
                    {{ Form::label('إسم المستخدم') }}
                    {{ Form::text('username', $user->username,array('required', 'class'=>'form-control', 'placeholder'=>'إسم المستخدم')) }}
                    {{ Form::label('الإيميل') }}
                    {{ Form::text('email', $user->email,array('required', 'disabled', 'class'=>'form-control', 'placeholder'=>'الإيميل')) }}
                    {{ Form::label('الصلاحيات') }}
                    {{ Form::select('groups',  ['' => 'برجاء الاختيار'] + $groups,$user->user_group, array('required', 'class' => 'form-control')) }}
                    <br>
                    {{ Form::label('المنطقة') }}
                    {{ Form::select('region', ['' => 'برجاء الاختيار'] + $regions,$user->region_id, array('class' => 'form-control', 'id' => 'region_selects','onchange' => 'doSomething(this.value)')) }}
                    <br>
                    {{ Form::hidden('invisible', '/ajax', array( 'id' => 'buildings_url')) }}
                    {{ Form::hidden('user_id', $user->id, array( 'id' => 'user_id')) }}
                    {{ Form::label('المبنى') }}
                    {{ Form::select('building', ['' => 'برجاء الاختيار'] + $regionBuildings,$user->building_id, array('class' => 'form-control', 'id' => 'building')) }}
                    <!--<select class="select_element form-control" id="building" name="building" required></select>-->
                    <br>
                    
                    {{ Form::submit('حفظ', array('class'=>'btn btn-primary')) }}
                    {{ Form::close() }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
