
<!-- username -->
<div class="form-group">
	<label class="control-label" for="username">Username</label>
	<div class="controls">
		@if (isset($user)) {{{ $user->username }}} @endif
	</div>
</div>
<!-- ./ username -->

<!-- fullname -->
<div class="form-group">
	<label class="control-label" for="fullname">Full Name</label>
	<div class="controls">
		@if (isset($user)) {{{ $user->fullname }}} @endif
	</div>
</div>
<!-- ./ fullname -->

<!-- Email -->
<div class="form-group">
	<label class="control-label" for="email">Email</label>
	<div class="controls">
		@if (isset($user)) {{{ $user->email }}} @endif
	</div>
</div>
<!-- ./ email -->

<!-- Activation Status -->
<div class="form-group">
	<label class="control-label" for="confirm">Activated?</label>
	<div class="controls">
		@if (isset($user)) {{{ $user->confirmed }}} @endif
	</div>
</div>
<!-- ./ activation status -->

<!-- Groups -->
<div class="form-group">
    <label class="control-label" for="roles">Roles</label>
    <div class="controls">            
        @foreach ($roles as $role)
			{{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? $role->name : '') }}}
        @endforeach
	</div>
</div>
<!-- ./ groups -->

<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('admin/users') }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('admin/users/' . $user->id . '/edit') }}}" class="btn btn-primary">Edit User</a>
		@else
			<a href="{{{ URL::to('admin/users') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>