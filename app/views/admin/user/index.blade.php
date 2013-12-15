@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header clearfix">
		<h3 class="pull-left">{{{ $title }}}</h3>
		<div class="pull-right">
			<a href="{{{ URL::to('admin/users/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span> Create New User</a>
		</div>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="users" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('admin/user/table.username') }}}</th>
				<th>{{{ Lang::get('admin/user/table.fullname') }}}</th>
				<th>{{{ Lang::get('admin/user/table.email') }}}</th>
				<th>{{{ Lang::get('admin/user/table.roles') }}}</th>
				<th>{{{ Lang::get('admin/user/table.activated') }}}</th>
				<th>{{{ Lang::get('admin/user/table.created_at') }}}</th>
				<th>{{{ Lang::get('table.actions') }}}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
@stop

{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
				oTable = $('#users').dataTable( {
				"sDom": "<l><f><r>t<i><p>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('admin/users/data') }}"
			});

			$("#users_filter input").addClass("form-control inline-control input-sm");
			$("#users_length select").addClass("form-control inline-control");
		});
	</script>
@stop