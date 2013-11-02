{{-- Role Details --}}
<!-- name -->
<div class="form-group">
	<label class="control-label" for="name">Name</label>
	<div class="controls">
		@if (isset($role)) {{{ $role->name }}} @endif
	</div>
</div>
<!-- ./ name -->

<!-- description -->
<div class="form-group">
	<label class="control-label" for="name">Description</label>
	<div class="controls">
		@if (isset($role)) {{{ $role->description }}} @endif
	</div>
</div>
<!-- ./ description -->

<fieldset>
<legend>Permissions</legend>
<div class="form-group">
    @foreach ($permissions as $permission)
    <div class="checkbox">
        <label>
            <input type="hidden" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="0" />
            <input type="checkbox" disabled="disabled" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="1"{{{ (isset($permission['checked']) && $permission['checked'] == true ? ' checked="checked"' : '')}}} />
            {{{ $permission['display_name'] }}}
        </label>
	</div>
    @endforeach
</div>
</fieldset>

<!-- Form Actions -->
<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('admin/roles') }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('admin/roles/' . $role->id . '/edit') }}}" class="btn btn-primary">Edit Role</a>
		@else
			<a href="{{{ URL::to('admin/roles') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>
<!-- ./ form actions -->