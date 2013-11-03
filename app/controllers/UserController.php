<?php
/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/

class UserController extends BaseController
{
    /**
     * User Model
     * @var User
     */
    protected $user;

    /**
     * Inject the models.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Users settings page
     *
     * @return View
     */
    public function index()
    {

        list($user,$redirect) = $this->user->checkAuthAndRedirect('user');
        if ($redirect) {return $redirect;}

        // Show the page
        return View::make('user/index', compact('user'));
    }

    /**
     * Update a user
     *
     */
    public function update($user)
    {
        if ($user->id != Auth::user()->id) {
            return Redirect::to('/')
                ->with( 'error', 'access forbidden' );
        }

        // If the 'admin' user is updating their own settings, the username will have been
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
        } else {
           // Validate the inputs
          $rules = array(
              'username'=> 'required|alpha_dash|unique:users,username,'. $user->id,
              'fullname'=> 'required',
              'email' => 'required|email|unique:users,email,'. $user->id,
              'password' => 'between:4,11|confirmed',
              'password_confirmation' => 'between:4,11',
              );
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $oldUser = clone $user;
            if ($user->username != 'admin') {
              $user->username = Input::get( 'username' );
            }
            $user->fullname = Input::get( 'fullname' );
            $user->email = Input::get( 'email' );
            $password = Input::get( 'password' );
            $passwordConfirmation = Input::get( 'password_confirmation' );

            if (!empty($password)) {
                if ($password === $passwordConfirmation) {
                    $user->password = $password;
                    // The password confirmation will be removed from model
                    // before saving. This field will be used in Ardent's
                    // auto validation.
                    $user->password_confirmation = $passwordConfirmation;
                } else {
                    // Redirect to the new user page
                    return Redirect::to('user')->with('error', Lang::get('admin/users/messages.password_does_not_match'));
                }
            } else {
                unset($user->password);
                unset($user->password_confirmation);
            }

            // Save if valid. Password field will be hashed before save
            $user->amend($rules);
            $error = $user->errors()->all();

            if (empty($error)) {
                // Redirect to the new user page
                return Redirect::to('user')->with('success', Lang::get('admin/users/messages.edit.success'));
            } else {
                //return Redirect::to('admin/users/' . $user->id . '/edit')->with('error', Lang::get('admin/users/messages.edit.failure'));
                return Redirect::to('user')
                    ->withInput(Input::except('password','password_confirmation'))
                    ->with( 'error', $error );
            }
        } else {
            // Form validation failed
            return Redirect::to('user')->withInput()->withErrors($validator);
        }
    }

    /**
     * Displays the login form
     *
     */
    public function login()
    {
        if ( Confide::user() ) {
            // If user is logged, redirect to internal
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        } else {
            return View::make('user/login');
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = array(
            'email'    => Input::get( 'email' ), // May be the username too
            'username' => Input::get( 'email' ), // so we have to pass both
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
        );

        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        if ( Confide::logAttempt( $input ) ) {
            // If the session 'loginRedirect' is set, then redirect
            // to that route. Otherwise redirect to '/'
            $r = Session::get('loginRedirect');
            if (!empty($r)) {
                Session::forget('loginRedirect');

                return Redirect::to($r);
            }

            return Redirect::to('/'); // change it to '/admin', '/dashboard' or something
        } else {
            $user = new User;

            // Check if there was too many login attempts
            if ( Confide::isThrottled( $input ) ) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ( $user->checkUserExists( $input ) and ! $user->isConfirmed( $input ) ) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

                        return Redirect::action('UserController@login')
                            ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param string $code
     */
    public function confirm( $code )
    {
        if ( Confide::confirm( $code ) ) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');

                        return Redirect::action('UserController@login')
                            ->with( 'notice', $notice_msg );
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');

                        return Redirect::action('UserController@login')
                            ->with( 'error', $error_msg );
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function forgot_password()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function do_forgot_password()
    {
        if ( Confide::forgotPassword( Input::get( 'email' ) ) ) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');

                        return Redirect::action('UserController@login')
                            ->with( 'notice', $notice_msg );
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');

                        return Redirect::action('UserController@forgot_password')
                            ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function reset_password( $token )
    {
        return View::make(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     */
    public function do_reset_password()
    {
        $input = array(
            'token'=>Input::get( 'token' ),
            'password'=>Input::get( 'password' ),
            'password_confirmation'=>Input::get( 'password_confirmation' ),
        );

        // By passing an array with the token, password and confirmation
        if ( Confide::resetPassword( $input ) ) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');

                        return Redirect::action('UserController@login')
                            ->with( 'notice', $notice_msg );
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');

                        return Redirect::action('UserController@reset_password', array('token'=>$input['token']))
                            ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout()
    {
        Confide::logout();

        return Redirect::to('/');
    }

}
