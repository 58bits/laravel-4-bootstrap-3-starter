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
			<a href="{{{ URL::to('admin/roles/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span> Create New Role</a>
		</div>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="roles" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('admin/role/table.name') }}}</th>
				<th>{{{ Lang::get('admin/role/table.description') }}}</th>
				<th>{{{ Lang::get('admin/role/table.users') }}}</th>
				<th>{{{ Lang::get('admin/role/table.created_at') }}}</th>
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
			oTable = $('#roles').dataTable( {
				"sDom": "<l><f><r>t<i><p>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('admin/roles/data') }}"
			});

			$("#roles_filter input").addClass("form-control inline-control input-sm");
			$("#roles_length select").addClass("form-control inline-control");
		});
	</script>
@stop