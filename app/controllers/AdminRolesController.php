<?php

class AdminRolesController extends AdminController {


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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = Lang::get('admin/role/title.role_management');

        // Grab all the groups
        $roles = $this->role;

        // Show the page
        return View::make('admin/role/index', compact('roles', 'title'));
    }

    /**
     * Show a single role details page.
     *
     * @return View
     */
    public function show($role)
    {
        if($role->id)
        {
            $permissions = $this->permission->preparePermissionsForDisplay($role->perms()->get());
        }
        else
        {
            // Redirect to the role management page
            return Redirect::to('admin/role')->with('error', Lang::get('admin/role/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('admin/role/title.role_show');

        // Show the page
        return View::make('admin/role/show', compact('role', 'permissions', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all the available permissions
        $permissions = $this->permission->all();

        // Selected permissions
        $selectedPermissions = Input::old('permissions', array());

        // Title
        $title = Lang::get('admin/role/title.create_a_new_role');

        // Show the page
        return View::make('admin/role/create', compact('permissions', 'selectedPermissions', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // Validate the inputs
        $rules = array(
            'name'=> 'required|alpha_dash|unique:roles,name',
            'description'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);
        // Check if the form validates with success
        if ($validator->passes())
        {
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $this->role->name = $inputs['name'];
            $this->role->description = $inputs['description'];
            $this->role->save($rules);

            if ( $this->role->id )
            {
                // Save permissions
                $this->role->perms()->sync($this->permission->preparePermissionsForSave($inputs['permissions']));
                
                // Redirect to the new role page
                return Redirect::to('admin/role/' . $this->role->id . '/edit')->with('success', Lang::get('admin/role/messages.create.success'));

            }
            else {
                // Redirect to the role create page
                //var_dump($this->role);
                return Redirect::to('admin/role/create')->with('error', Lang::get('admin/role/messages.create.error'));
            }
        }
        else {
            // Form validation failed
            return Redirect::to('admin/role/create')->withInput()->withErrors($validator);
        }
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param $role
     * @return Response
     */
    public function edit($role)
    {
        if($role)
        {
            $permissions = $this->permission->preparePermissionsForDisplay($role->perms()->get());
        }
        else
        {
            // Redirect to the role management page
            return Redirect::to('admin/role')->with('error', Lang::get('admin/role/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('admin/role/title.role_update');

        // Show the page
        return View::make('admin/role/edit', compact('role', 'permissions', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $role
     * @return Response
     */
    public function update($role)
    {
        // If the 'admin' role is being updated, the name of this role will have been 
        // disabled in the form, and so won't be present in the form POST values.
        // We'll change the rules here accordingly. The admin role cannot be renamed
        // or deleted.

        if ($role->name == 'admin') {
            $rules = array(
                'description' => 'required'
            );
        }
        else {
            $rules = array(
                'name'=> 'required|alpha_dash|unique:roles,name,' . $role->id,
                'description' => 'required'
            );
        }

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            // Update the role data
            if ($role->name != 'admin') {
                $role->name        = Input::get('name');
            }
            $role->description = Input::get('description');
            $role->perms()->sync($this->permission->preparePermissionsForSave(Input::get('permissions')));

            // Was the role updated?
            if ($role->save($rules))
            {
                // Redirect to the role page
                return Redirect::to('admin/role/' . $role->id . '/edit')->with('success', Lang::get('admin/role/messages.update.success'));
            }
            else
            {
                // Redirect to the role page
                return Redirect::to('admin/role/' . $role->id . '/edit')->with('error', Lang::get('admin/role/messages.update.error'));
            }
        }
        else {
            // Form validation failed
            return Redirect::to('admin/role/' . $role->id . '/edit')->withInput()->withErrors($validator);
        }
    }


    /**
     * Remove user page.
     *
     * @param $role
     * @return Response
     */
    public function delete($role)
    {
        // Title
        $title = Lang::get('admin/role/title.role_delete');

        if($role->id)
        {
            $permissions = $this->permission->preparePermissionsForDisplay($role->perms()->get());
        }
        else
        {
            // Redirect to the role management page
            return Redirect::to('admin/role')->with('error', Lang::get('admin/role/messages.does_not_exist'));
        }

        // Show the record
        return View::make('admin/role/delete', compact('role', 'permissions', 'title'));
    }

    /**
     * Remove the specified user from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($role)
    {
        // Was the role deleted?
        if($role->delete()) {
            // Redirect to the role management page
            return Redirect::to('admin/role')->with('success', Lang::get('admin/role/messages.delete.success'));
        }

        // There was a problem deleting the role
        return Redirect::to('admin/role')->with('error', Lang::get('admin/role/messages.delete.error'));            
    }

    /**
     * Show a list of all the roles formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $roles = Role::select(array('roles.id',  'roles.name', 'roles.description', 'roles.id as users', 'roles.created_at'));

        return Datatables::of($roles)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')
        ->edit_column('users', '{{{ DB::table(\'assigned_roles\')->where(\'role_id\', \'=\', $id)->count()  }}}')


        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'admin/role/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'admin/role/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    @if($name == \'admin\')
                    @else
                    <li><a href="{{{ URL::to(\'admin/role/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                    @endif
                  </ul>
                </div>')

        ->remove_column('id')

        ->make();
    }

}