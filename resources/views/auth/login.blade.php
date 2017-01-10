@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">تسجيل الدخول</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            

                            <div class="col-md-6 one_form_element">
                                <label for="username" class="col-md-4 control-label">إسم المستخدم</label>
                                <input id="email" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                                
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                                
                            </div>
                            
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">


                            <div class="col-md-6 one_form_element">
                                <label for="password" class="col-md-4 control-label">كلمة المرور</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4 one_form_element">
                                <button type="submit" class="btn btn-primary">
                                    تسجيل دخول
                                </button>
                            </div>

                        </div>
                        <div class="col-md-8 col-md-offset-4 warning">
                            @if(session('errorLogin'))
                            <label class="label-warning">{{ session('errorLogin') }}</label>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
