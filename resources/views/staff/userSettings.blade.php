<?php
//use Symfony\Component\DomCrawler\Form;
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">الإعدادات</div>
                <div class="panel-body">
                    {{ Form::open(array('url' => 'users/editsettingsaction')) }}
                    {{ Form::label('الإسم') }}
                    {{ Form::text('name', $user->name,array('disabled', 'class'=>'form-control', 'placeholder'=>'الإسم')) }}
                    {{ Form::label('إسم المستخدم') }}
                    {{ Form::text('username', $user->username,array('disabled', 'class'=>'form-control', 'placeholder'=>'إسم المستخدم')) }}
                    {{ Form::label('الإيميل') }}
                    {{ Form::text('email', $user->email,array('required', 'class'=>'form-control', 'placeholder'=>'الإيميل')) }}
                    {{ Form::label('تلقي إيميل مع الطلبات الجديدة') }}
                    {{ Form::select('email_enabled',  ['0' => 'لا', '1'=> 'نعم'],$user->email_enabled, array('required', 'class' => 'form-control')) }}
                    {{ Form::hidden('user_id', $user->id, array('id' => 'user_id')) }}
                    <br>
                    
                    <br>
                    
                    {{ Form::submit('حفظ', array('class'=>'btn btn-primary')) }}
                    {{ Form::close() }}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
