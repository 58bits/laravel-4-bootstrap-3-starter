@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="page-header clearfix">
			<h3 class="pull-left">{{{ $title }}}</h3>
			<div class="pull-right">
				<a href="{{{ URL::to('admin/roles') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
			</div>
		</div>

		<!-- Notifications -->
	    @include('notifications')
	    <!-- ./ notifications -->
		<div class="details">
		@include('admin/role/_details', compact('role', 'permissions'))
		</div>
	</div>
</div>
@stop