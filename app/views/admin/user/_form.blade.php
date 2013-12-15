{{-- Create User Form --}}
@if (isset($user))
{{ Form::open(array('url' => URL::to('admin/users') . '/' . $user->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('admin/users'), 'method' => 'post', 'class' => 'bf')) }}
@endif

<div class="row">
  		<div class="col-xs-6">
			<!-- username -->
			<div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
				<label class="control-label" for="username">Username</label>
				<div class="controls">
					<input class="form-control" type="text" {{{ ((isset($user) && $user->username == 'admin') ? ' disabled="disabled"' : '') }}} name="username" id="username" value="{{{ Input::old('username', isset($user) ? $user->username : null) }}}" />
					<span class="help-block">{{{ $errors->first('username', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ username -->

			<!-- username -->
			<div class="form-group {{{ $errors->has('fullname') ? 'has-error' : '' }}}">
				<label class="control-label" for="fullname">Full Name</label>
				<div class="controls">
					<input class="form-control" type="text" name="fullname" id="fullname" value="{{{ Input::old('fullname', isset($user) ? $user->fullname : null) }}}" />
					<span class="help-block">{{{ $errors->first('fullname', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ username -->

			<!-- Email -->
			<div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
				<label class="control-label" for="email">Email</label>
				<div class="controls">
					<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', isset($user) ? $user->email : null) }}}" />
					<span class="help-block">{{{ $errors->first('email', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ email -->

			<!-- Password -->
			<div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<input class="form-control" type="password" name="password" id="password" value="" />
					<span class="help-block">{{{ $errors->first('password', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ password -->

			<!-- Password Confirm -->
			<div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
				<label class="control-label" for="password_confirmation">Password Confirm</label>
				<div class="controls">
					<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
					<span class="help-block">{{{ $errors->first('password_confirmation', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ password confirm -->

  		</div>
  		<div class="col-xs-6">
		<!-- Activation Status -->
			<div class="form-group {{{ $errors->has('activated') || $errors->has('confirm') ? 'has-error' : '' }}}">
				<label class="control-label" for="confirm">Activate User?</label>
				<div class="controls">
					@if ($action == 'create')
						<select class="form-control" name="confirm" id="confirm">
							<option value="1"{{{ (Input::old('confirm', 0) === 1 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
							<option value="0"{{{ (Input::old('confirm', 0) === 0 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
						</select>
					@else
						<select class="form-control"  {{{ ($user->id === Confide::user()->id ? ' disabled="disabled"' : '') }}} name="confirm" id="confirm">
							<option value="1"{{{ ($user->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
							<option value="0"{{{ ( ! $user->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
						</select>
					@endif
					{{{ $errors->first('confirm', '<span class="help-inline">:message</span>') }}}
				</div>
			</div>
			<!-- ./ activation status -->

			<!-- roles -->
			<div class="form-group {{{ $errors->has('roles') ? 'has-error' : '' }}}">
		        <label class="control-label" for="roles">Roles</label>
		        <div class="controls">
		            <select class="form-control" name="roles[]" id="roles[]" multiple>
		                    @foreach ($roles as $role)
								@if ($action == 'create')
		                    		<option value="{{{ $role->id }}}"{{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
		                    	@else
									<option value="{{{ $role->id }}}"{{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
								@endif
		                    @endforeach
					</select>

					<span class="help-block">
						Select a role to assign to the user, remember that a user takes on the permissions of the role they are assigned.
					</span>
		    	</div>
			</div>
			<!-- ./ roles --> 
  		</div>
	</div>
		
	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('admin/users') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->

{{ Form::close() }}
