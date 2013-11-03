<?php
/*
|--------------------------------------------------------------------------
| Default Home Controller
|--------------------------------------------------------------------------
|
| You may wish to use controllers instead of, or in addition to, Closure
| based routes. That's great! Here is an example controller method to
| get you started. To route to this controller, just add the route:
|
|	Route::get('/', 'HomeController@showWelcome');
|
*/

class HomeController extends BaseController
{
    public function showWelcome()
    {
        return View::make('home');
    }

    public function showSecret()
    {
        return View::make('secret');
    }

}
