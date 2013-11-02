{{-- Create Role Form --}}
@if (isset($role))
{{ Form::open(array('url' => URL::to('admin/roles') . '/' . $role->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('admin/roles'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- role name -->
	<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
		<label class="control-label" for="name">Name</label>
		<div class="controls">
			<input class="form-control" type="text" {{{ ((isset($role) && $role->name === 'admin') ? ' disabled="disabled"' : '') }}} name="name" id="name" value="{{{ Input::old('name', isset($role) ? $role->name : null) }}}" />
			<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ role name -->

	<!-- role description -->
	<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
		<label class="control-label" for="description">Description</label>
		<div class="controls">
			<input class="form-control" type="text" name="description" id="description" value="{{{ Input::old('description', isset($role) ? $role->description : null) }}}" />
			<span class="help-block">{{{ $errors->first('description', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ role name -->
	
	<fieldset>
	<legend>Permissions</legend>
    <div class="form-group">
        @foreach ($permissions as $permission)
        <div class="checkbox">
            <label>
                <input type="hidden" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="0" />
                <input type="checkbox" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="1"{{{ (isset($permission['checked']) && $permission['checked'] == true ? ' checked="checked"' : '')}}} />
                {{{ $permission['display_name'] }}}
            </label>
    	</div>
        @endforeach
    </div>
	</fieldset>

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('admin/roles') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}