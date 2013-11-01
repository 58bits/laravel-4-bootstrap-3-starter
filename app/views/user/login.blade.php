@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.login') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h3>Log in</h3>
        </div>

        <!-- Notifications -->
        @include('notifications')
        <!-- ./ notifications -->
 
        <form class="bf" role="form" method="POST" action="{{ URL::to('user/login') }}" accept-charset="UTF-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="control-label" for="email">{{ Lang::get('confide::confide.username_e_mail') }}</label>
                
                <input class="form-control" tabindex="1" placeholder="{{ Lang::get('confide::confide.username_e_mail') }}" type="text" name="email" id="email" value="{{ Input::old('email') }}">
            </div>
            <div class="form-group">

                <label class="control-label" for="password">
                    {{ Lang::get('confide::confide.password') }}
                    <small>
                        <a href="forgot">{{ Lang::get('confide::confide.login.forgot_password') }}</a>
                    </small>
                </label>

                
                <input class="form-control" tabindex="2" placeholder="{{ Lang::get('confide::confide.password') }}" type="password" name="password" id="password">
                
            </div>
            <div class="checkbox">
                <label for="remember">{{ Lang::get('confide::confide.login.remember') }}
                    <input type="hidden" name="remember" value="0">
                    <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
                </label>
            </div>

            <button tabindex="3" type="submit" class="btn btn-primary">{{ Lang::get('confide::confide.login.submit') }}</button>
            
        </form>
    </div>
</div>
@stop
