@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
    {{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h3>
                {{{ $title }}}

                <div class="pull-right">
                    <a href="{{{ URL::to('admin/user') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
                </div>
            </h3>
        </div>

        <!-- Notifications -->
        @include('notifications')
        <!-- ./ notifications -->
        
        {{-- Delete User Form --}}
        {{ Form::open(array('url' => URL::to('admin/user') . '/' . $user->id, 'method' => 'delete', 'class' => 'bf')) }}
        
           @include('admin/user/_details', compact('user'))

        {{ Form::close() }}
    </div>
</div>
@stop