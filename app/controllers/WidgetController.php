<?php
/*
|--------------------------------------------------------------------------
| Widget Controller
|--------------------------------------------------------------------------
|
| An example controller that uses the pre-baked RESTful resource controller
| actions for index, create, store, show, edit, update, destroy, as well as a
| delete method to show the record before deletion.
|
| See routes.php  ->
| Route::resource('widget', 'WidgetController');
| Route::get('widget/{widget}/delete', 'WidgetController@delete');
|
*/

class WidgetController extends BaseController
{
    /**
     * User Model
     * @var User
     */
    protected $widget;

    /**
     * Inject the models.
     * @param Widget $widget
     */
    public function __construct(Widget $widget)
    {
        parent::__construct();
        $this->widget = $widget;
    }

    /**
     * Display a listing of the resource.
     *
     * See public function data() below for the data source for the list,
     * and the view/widget/index.blade.php for the jQuery script that makes
     * the Ajax request.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = Lang::get('widget/title.widget_management');

        // Show the page
        return View::make('widget/index', compact('title'));
    }

    /**
     * Show a single widget details page.
     *
     * @return View
     */
    public function show($widget)
    {
        // Title
        $title = Lang::get('widget/title.widget_show');

        // Show the page
        return View::make('widget/show', compact('widget', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Title
        $title = Lang::get('widget/title.create_a_new_widget');

        // Show the page
        return View::make('widget/create', compact('title'));
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
            'name'=> 'required|alpha_dash|unique:widgets,name',
            'description'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $this->widget->name = $inputs['name'];
            $this->widget->description = $inputs['description'];
            $this->widget->save($rules);

            if ($this->widget->id) {
                // Redirect to the new widget page
                return Redirect::to('widgets')->with('success', Lang::get('widget/messages.create.success'));

            } else {
                // Redirect to the widget create page
                //var_dump($this->widget);
                return Redirect::to('widgets/create')->with('error', Lang::get('widget/messages.create.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('widgets/create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $widget
     * @return Response
     */
    public function edit($widget)
    {
        if ($widget) {

        } else {
            // Redirect to the widget management page
            return Redirect::to('widgets')->with('error', Lang::get('widget/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('widget/title.widget_update');

        // Show the page
        return View::make('widget/edit', compact('widget', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $widget
     * @return Response
     */
    public function update($widget)
    {

        $rules = array(
                'name'=> 'required|alpha_dash|unique:widgets,name,' . $widget->id,
                'description' => 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            $widget->name        = Input::get('name');
            $widget->description = Input::get('description');

            // Was the widget updated?
            if ($widget->save($rules)) {
                // Redirect to the widget page
                return Redirect::to('widgets/' . $widget->id . '/edit')->with('success', Lang::get('widget/messages.update.success'));
            } else {
                // Redirect to the widget page
                return Redirect::to('widgets/' . $widget->id . '/edit')->with('error', Lang::get('widget/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('widgets/' . $widget->id . '/edit')->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove user page.
     *
     * @param $widget
     * @return Response
     */
    public function delete($widget)
    {
        // Title
        $title = Lang::get('widget/title.widget_delete');

        if ($widget->id) {

        } else {
            // Redirect to the widget management page
            return Redirect::to('widgets')->with('error', Lang::get('widget/messages.does_not_exist'));
        }

        // Show the record
        return View::make('widget/delete', compact('widget', 'title'));
    }

    /**
     * Remove the specified user from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($widget)
    {
        // Was the widget deleted?
        if ($widget->delete()) {
            // Redirect to the widget management page
            return Redirect::to('widgets')->with('success', Lang::get('widget/messages.delete.success'));
        }

        // There was a problem deleting the widget
        return Redirect::to('widgets')->with('error', Lang::get('widget/messages.delete.error'));
    }

    /**
     * Show a list of all the widgets formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        //Make this method testable and mockable by using our injected $widget member.
        $widgets = $this->widget->select(array('widgets.id',  'widgets.name', 'widgets.description', 'widgets.created_at'));

        return Datatables::of($widgets)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'widgets/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'widgets/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'widgets/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                  </ul>
                </div>')

        ->remove_column('id')

        ->make();
    }
}
