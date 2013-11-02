{{-- Create Role Form --}}
@if (isset($widget))
{{ Form::open(array('url' => URL::to('widgets') . '/' . $widget->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('widgets'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- widget name -->
	<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
		<label class="control-label" for="name">Name</label>
		<div class="controls">
			<input class="form-control" type="text" {{{ ((isset($widget) && $widget->name === 'admin') ? ' disabled="disabled"' : '') }}} name="name" id="name" value="{{{ Input::old('name', isset($widget) ? $widget->name : null) }}}" />
			<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ widget name -->

	<!-- widget description -->
	<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
		<label class="control-label" for="description">Description</label>
		<div class="controls">
			<input class="form-control" type="text" name="description" id="description" value="{{{ Input::old('description', isset($widget) ? $widget->description : null) }}}" />
			<span class="help-block">{{{ $errors->first('description', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ widget name -->

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('widgets') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}