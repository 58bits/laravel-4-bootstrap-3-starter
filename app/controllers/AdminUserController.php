<?php
/*
|--------------------------------------------------------------------------
| AdminUserController
|--------------------------------------------------------------------------
|
*/

class AdminUserController extends AdminController {

    /**
     * User Model
     * @var User
     */
    protected $user;

    /**
     * Role Model
     * @var Role
     */
    protected $role;

    /**
     * Permission Model
     * @var Permission
     */
    protected $permission;

    /**
     * Inject the models.
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(User $user, Role $role, Permission $permission)
    {
        parent::__construct();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }
    
    /**
     * Users
     *
     * @return View
     */
    public function index()
    {
        // Title
        $title = Lang::get('admin/user/title.user_management');

        // The list of users will be filled later using the JSON Data method
        // below - to populate the DataTables table.

        return View::make('admin/user/index', compact('title'));
    }

    /**
     * Show a single user details page.
     *
     * @return View
     */
    public function show($user)
    {
        if ( $user->id )
        {
            $roles = $this->role->all();
            $permissions = $this->permission->all();

             // Title
            $title = Lang::get('admin/user/title.user_show');
            
            return View::make('admin/user/show', compact('user', 'roles', 'permissions', 'title'));
        }
        else
        {
            return Redirect::to('admin/user')->with('error', Lang::get('admin/user/messages.does_not_exist'));
        }
    }


    /**
     * Displays the form for user creation
     *
     */
    public function create()
    {
        
        // Title
        $title = Lang::get('admin/user/title.create_a_new_user');

        // All roles
        $roles = $this->role->all();        

        // Selected roles 
        $selectedRoles = Input::old('roles', array());

        // Show the page
        return View::make('admin/user/create', compact('roles', 'selectedRoles', 'title'));
    }

    /**
     * Stores new account
     *
     */
    public function store()
    {
        
        // Validate the inputs
        $rules = array(
            'username' => 'required|alpha_dash|unique:users,username',
            'fullname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'between:4,11|confirmed',
            'password_confirmation' => 'between:4,11',
            );

        $this->user->username = Input::get( 'username' );
        $this->user->fullname = Input::get( 'fullname' );
        $this->user->email = Input::get( 'email' );
        $this->user->password = Input::get( 'password' );

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $this->user->password_confirmation = Input::get( 'password_confirmation' );
        $this->user->confirmed = Input::get( 'confirm' );

        // Permissions are currently tied to roles. Can't do this yet.
        //$user->permissions = $user->roles()->preparePermissionsForSave(Input::get( 'permissions' ));

        // Save if valid. Password field will be hashed before save
        $this->user->save($rules);

        if ( $this->user->id )
        {
            // Save roles. Handles updating.
            $this->user->saveRoles(Input::get( 'roles' ));

            // Redirect to the new user page
            //return Redirect::to('users/' . $this->user->id . '/edit')->with('success', Lang::get('admin/users/messages.create.success'));
            return Redirect::to('admin/user')->with('success', Lang::get('admin/user/messages.create.success'));
        }
        else
        {
            // Get validation errors (see Ardent package)
            $error = $this->user->errors()->all();

            return Redirect::to('admin/user/create')
                ->withInput(Input::except('password'))
                ->with( 'error', $error );
        }
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function edit($user)
    {
        if ( $user->id )
        {
            $roles = $this->role->all();
            $permissions = $this->permission->all();

             // Title
            $title = Lang::get('admin/user/title.user_update');
            
            return View::make('admin/user/edit', compact('user', 'roles', 'permissions', 'title'));
        }
        else
        {
            return Redirect::to('admin/user')->with('error', Lang::get('admin/user/messages.does_not_exist'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function update($user)
    {
        // If the 'admin' user is being updated, the username of this user will have been 
        // disabled in the form, and so won't be present in the form POST values.
        // We'll change the rules here accordingly. The admin user cannot be renamed
        // or deleted.

        if ($user->username == 'admin') {

          // Validate the inputs
          $rules = array(
              'fullname'=> 'required',
              'email' => 'required|email|unique:users,email,'. $user->id,
              'password' => 'between:4,11|confirmed',
              'password_confirmation' => 'between:4,11',
              );
        }
        else {
           // Validate the inputs
          $rules = array(
              'username'=> 'required|unique:users,username,'. $user->id,
              'fullname'=> 'required',
              'email' => 'required|email|unique:users,email,'. $user->id,
              'password' => 'between:4,11|confirmed',
              'password_confirmation' => 'between:4,11',
              );
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes())
        {
            $oldUser = clone $user;
            if ($user->username != 'admin') {
              $user->username = Input::get( 'username' );
              $user->confirmed = Input::get( 'confirm' );
            }
            $user->fullname = Input::get( 'fullname' );
            $user->email = Input::get( 'email' );
            $password = Input::get( 'password' );
            $passwordConfirmation = Input::get( 'password_confirmation' );

            if(!empty($password)) {
                if($password === $passwordConfirmation) {
                    $user->password = $password;
                    // The password confirmation will be removed from model
                    // before saving. This field will be used in Ardent's
                    // auto validation.
                    $user->password_confirmation = $passwordConfirmation;
                } else {
                    // Redirect to the new user page
                    return Redirect::to('admin/user/' . $user->id . '/edit')->with('error', Lang::get('admin/user/messages.password_does_not_match'));
                }
            } else {
                unset($user->password);
                unset($user->password_confirmation);
            }

            // Save if valid. Password field will be hashed before save
            $user->amend($rules);
            $error = $user->errors()->all();

            if(empty($error)) {
                // Save roles. Handles updating.
                $user->saveRoles(Input::get( 'roles' ));
            }

            if(empty($error)) {
                // Redirect to the new user page
                return Redirect::to('admin/user/' . $user->id . '/edit')->with('success', Lang::get('admin/user/messages.edit.success'));
            } else {
                //return Redirect::to('admin/users/' . $user->id . '/edit')->with('error', Lang::get('admin/users/messages.edit.failure'));
                return Redirect::to('admin/user/' . $user->id . '/edit')
                    ->withInput(Input::except('password','password_confirmation'))
                    ->with( 'error', $error );
            }
        }
        else
        {
            // Form validation failed
            return Redirect::to('admin/user/' . $user->id . '/edit')->withInput()->withErrors($validator);
        }        
    }

    /**
     * Remove user.
     *
     * @param $user
     * @return Response
     */
    public function delete($user)
    {
        if ( $user->id )
        {
            $roles = $this->role->all();
            $permissions = $this->permission->all();

             // Title
            $title = Lang::get('admin/user/title.user_delete');
            
            return View::make('admin/user/delete', compact('user', 'roles', 'permissions', 'title'));
        }
        else
        {
            return Redirect::to('admin/user')->with('error', Lang::get('admin/user/messages.does_not_exist'));
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @return Response
     */
    public function destroy($user)
    {
        // Check if we are not trying to delete ourselves
        if ($user->id === Confide::user()->id)
        {
            // Redirect to the user management page
            return Redirect::to('admin/user')->with('error', Lang::get('admin/user/messages.delete.impossible'));
        }

        AssignedRoles::where('user_id', $user->id)->delete();

        $id = $user->id;
        $user->delete();

        // Was the comment post deleted?
        $user = User::find($id);
        if (empty($user) )
        {
            // TODO needs to delete all of that user's content
            return Redirect::to('admin/user')->with('success', Lang::get('admin/user/messages.delete.success'));
        }
        else
        {
            // There was a problem deleting the user
            return Redirect::to('admin/user')->with('error', Lang::get('admin/user/messages.delete.error'));
        }
    }

    /**
     * Show a list of all the users formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $users = User::leftjoin('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                    ->leftjoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
                    ->select(array('users.id', 'users.username', 'users.fullname', 'users.email', 'roles.name as rolename', 'users.confirmed', 'users.created_at'));

        return Datatables::of($users)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->edit_column('confirmed','@if($confirmed)
                            Yes
                        @else
                            No
                        @endif')

        ->add_column('actions', '<div class="btn-group">
                      <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                        Action <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="{{{ URL::to(\'admin/user/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                        <li><a href="{{{ URL::to(\'admin/user/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                        @if($username == \'admin\')
                        @else
                            <li><a href="{{{ URL::to(\'admin/user/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                        @endif
                      </ul>
                    </div>')

        ->remove_column('id')

        ->make();
    }
}