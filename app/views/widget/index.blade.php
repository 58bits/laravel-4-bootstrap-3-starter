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
				<a href="{{{ URL::to('widgets/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span> Create New Widget</a>
			</div>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="widgets" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('widget/table.name') }}}</th>
				<th>{{{ Lang::get('widget/table.description') }}}</th>
				<th>{{{ Lang::get('widget/table.created_at') }}}</th>
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
			oTable = $('#widgets').dataTable( {
				"sDom": "<l><f><r>t<i><p>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('widgets/data') }}"
			});

			$("#widgets_filter input").addClass("form-control inline-control input-sm");
			$("#widgets_length select").addClass("form-control inline-control");
		});
	</script>
@stop