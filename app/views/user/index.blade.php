@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.settings') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')

<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="page-header">
        <h3>Edit your settings</h3>
    </div>

    <!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->
    <form class="bf" role="form" method="post" action="{{ URL::to('user/' . $user->id . '/update') }}"  autocomplete="off">   
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- Username -->
            <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
                <label class="control-label" for="username">Username</label>
                <div class="controls">
                    <input class="form-control" tabindex="1" type="text" {{{ ((isset($user) && $user->username == 'admin') ? ' disabled="disabled"' : '') }}} name="username" id="username" value="{{{ Input::old('username', $user->username) }}}" />
                   <span class="help-block">{{{ $errors->first('username', ':message') }}}</span>
                </div>
            </div>
            <!-- ./ username -->

            <!-- Fullname -->
            <div class="form-group {{{ $errors->has('fullname') ? 'has-error' : '' }}}">
                <label class="control-label" for="fullname">Full Name</label>
                <div class="controls">
                    <input class="form-control" tabindex="1" type="text" name="fullname" id="fullname" value="{{{ Input::old('fullname', $user->fullname) }}}" />
                    <span class="help-block">{{{ $errors->first('fullname', ':message') }}}</span>
                </div>
            </div>
            <!-- ./ Fullname -->

            <!-- Email -->
            <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                    <input class="form-control" tabindex="2" type="text" name="email" id="email" value="{{{ Input::old('email', $user->email) }}}" />
                    <span class="help-block">{{{ $errors->first('email', ':message') }}}</span>
                </div>
            </div>
            <!-- ./ email -->

            <!-- Password -->
            <div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
                <label class="control-label" for="password">Password</label>
                <div class="controls">
                    <input class="form-control" tabindex="3" type="password" name="password" id="password" value="" />
                    <span class="help-block">{{{ $errors->first('password', ':message') }}}</span>
                </div>
            </div>
            <!-- ./ password -->

            <!-- Password Confirm -->
            <div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
                <label class="control-label" for="password_confirmation">Password Confirm</label>
                <div class="controls">
                    <input class="form-control" tabindex="4"  type="password" name="password_confirmation" id="password_confirmation" value="" />
                   <span class="help-block">{{{ $errors->first('password_confirmation', ':message') }}}</span>
                </div>
            </div>
            <!-- ./ password confirm -->

        <!-- Form Actions -->
        <div class="control-group">
            <div class="controls">
                <a href="{{{ URL::to('/') }}}" class="btn btn-primary">Cancel</a>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
        <!-- ./ form actions -->
    </form>
  </div>
</div>
@stop
