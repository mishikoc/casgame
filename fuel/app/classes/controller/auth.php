<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2018 Fuel Development Team
 * @link       http://fuelphp.com
 */

class Controller_Authorization extends Controller
{
public function action_login()
{
    // already logged in?
    if (\Auth::check())
    {
        // yes, so go back to the page the user came from, or the
        // application dashboard if no previous page can be detected
        \Messages::info(__('login.already-logged-in'));
        \Response::redirect_back('dashboard');
    }

    // was the login form posted?
    if (\Input::method() == 'POST')
    {
        // check the credentials.
        if (\Auth::instance()->login(\Input::param('username'), \Input::param('password')))
        {
            // did the user want to be remembered?
            if (\Input::param('remember', false))
            {
                // create the remember-me cookie
                \Auth::remember_me();
            }
            else
            {
                // delete the remember-me cookie if present
                \Auth::dont_remember_me();
            }

            // logged in, go back to the page the user came from, or the
            // application dashboard if no previous page can be detected
            \Response::redirect_back('dashboard');
        }
        else
        {
            // login failed, show an error message
            \Messages::error(__('login.failure'));
        }
    }

    // display the login page
    return \View::forge('login/login');
}
public function action_logout()
{
    // remove the remember-me cookie, we logged-out on purpose
    \Auth::dont_remember_me();

    // logout
    \Auth::logout();

    // inform the user the logout was successful
    \Messages::success(__('login.logged-out'));

    // and go back to where you came from (or the application
    // homepage if no previous page can be determined)
    \Response::redirect_back();
}
public function action_register()
{
    // is registration enabled?
    if ( ! \Config::get('application.user.registration', false))
    {
        // inform the user registration is not possible
        \Messages::error(__('login.registation-not-enabled'));

        // and go back to the previous page (or the homepage)
        \Response::redirect_back();
    }

    // create the registration fieldset
    $form = \Fieldset::forge('registerform');

    // add a csrf token to prevent CSRF attacks
    $form->form()->add_csrf();

    // and populate the form with the model properties
    $form->add_model('Model\\Auth_User');

    // add the fullname field, it's a profile property, not a user property
    $form->add_after('fullname', __('login.form.fullname'), array(), array(), 'username')->add_rule('required');

    // add a password confirmation field
    $form->add_after('confirm', __('login.form.confirm'), array('type' => 'password'), array(), 'password')->add_rule('required');

    // make sure the password is required
    $form->field('password')->add_rule('required');

    // and new users are not allowed to select the group they're in (duh!)
    $form->disable('group_id');

    // since it's not on the form, make sure validation doesn't trip on its absence
    $form->field('group_id')->delete_rule('required')->delete_rule('is_numeric');

    // was the registration form posted?
    if (\Input::method() == 'POST')
    {
        // validate the input
        $form->validation()->run();

        // if validated, create the user
        if ( ! $form->validation()->error())
        {
            try
            {
                // call Auth to create this user
                $created = \Auth::create_user(
                    $form->validated('username'),
                    $form->validated('password'),
                    $form->validated('email'),
                    \Config::get('application.user.default_group', 1),
                    array(
                        'fullname' => $form->validated('fullname'),
                    )
                );

                // if a user was created succesfully
                if ($created)
                {
                    // inform the user
                    \Messages::success(__('login.new-account-created'));

                    // and go back to the previous page, or show the
                    // application dashboard if we don't have any
                    \Response::redirect_back('dashboard');
                }
                else
                {
                    // oops, creating a new user failed?
                    \Messages::error(__('login.account-creation-failed'));
                }
            }

            // catch exceptions from the create_user() call
            catch (\SimpleUserUpdateException $e)
            {
                // duplicate email address
                if ($e->getCode() == 2)
                {
                    \Messages::error(__('login.email-already-exists'));
                }

                // duplicate username
                elseif ($e->getCode() == 3)
                {
                    \Messages::error(__('login.username-already-exists'));
                }

                // this can't happen, but you'll never know...
                else
                {
                    \Messages::error($e->getMessage());
                }
            }
        }

        // validation failed, repopulate the form from the posted data
        $form->repopulate();
    }

    // pass the fieldset to the form, and display the new user registration view
    return \View::forge('login/registration')->set('form', $form, false);
}
public function action_lostpassword($hash = null)
{
    // was the lostpassword form posted?
    if (\Input::method() == 'POST')
    {
        // do we have a posted email address?
        if ($email = \Input::post('email'))
        {
            // do we know this user?
            if ($user = \Model\Auth_User::find_by_email($email))
            {
                // generate a recovery hash
                $hash = \Auth::instance()->hash_password(\Str::random()).$user->id;

                // and store it in the user profile
                \Auth::update_user(
                    array(
                        'lostpassword_hash' => $hash,
                        'lostpassword_created' => time()
                    ),
                    $user->username
                );

                // send an email out with a reset link
                \Package::load('email');
                $email = \Email::forge();

                // use a view file to generate the email message
                $email->html_body(
                    \Theme::instance()->view('login/lostpassword')
                        ->set('url', \Uri::create('login/lostpassword/' . base64_encode($hash) . '/'), false)
                        ->set('user', $user, false)
                        ->render()
                );

                // give it a subject
                $email->subject(__('login.password-recovery'));

                // add from- and to address
                $from = \Config::get('application.email-addresses.from.website', 'website@example.org');
                $email->from($from['email'], $from['name']);
                $email->to($user->email, $user->fullname);

                // and off it goes (if all goes well)!
                try
                {
                    // send the email
                    $email->send();
                }

                // this should never happen, a users email was validated, right?
                catch(\EmailValidationFailedException $e)
                {
                    \Messages::error(__('login.invalid-email-address'));
                    \Response::redirect_back();
                }

                // what went wrong now?
                catch(\Exception $e)
                {
                    // log the error so an administrator can have a look
                    logger(\Fuel::L_ERROR, '*** Error sending email ('.__FILE__.'#'.__LINE__.'): '.$e->getMessage());

                    \Messages::error(__('login.error-sending-email'));
                    \Response::redirect_back();
                }
            }
        }

        // posted form, but email address posted?
        else
        {
            // inform the user and fall through to the form
            \Messages::error(__('login.error-missing-email'));
        }

        // inform the user an email is on the way (or not ;-))
        \Messages::info(__('login.recovery-email-send'));
        \Response::redirect_back();
    }

    // no form posted, do we have a hash passed in the URL?
    elseif ($hash !== null)
    {

        // decode the hash
        $hash = base64_decode($hash);

        // get the userid from the hash
        $user = substr($hash, 44);

        // and find the user with this id
        if ($user = \Model\Auth_User::find_by_id($user))
        {
            // do we have this hash for this user, and hasn't it expired yet (we allow for 24 hours response)?
            if (isset($user->lostpassword_hash) and $user->lostpassword_hash == $hash and time() - $user->lostpassword_created < 86400)
            {
                // invalidate the hash
                \Auth::update_user(
                    array(
                        'lostpassword_hash' => null,
                        'lostpassword_created' => null
                    ),
                    $user->username
                );

                // log the user in and go to the profile to change the password
                if (\Auth::instance()->force_login($user->id))
                {
                    \Messages::info(__('login.password-recovery-accepted'));
                    \Response::redirect('profile');
                }
            }
        }

        // something wrong with the hash
        \Messages::error(__('login.recovery-hash-invalid'));
        \Response::redirect_back();
    }

    // no form posted, and no hash present. no clue what we do here
    else
    {
        \Response::redirect_back();
    }
}
}
