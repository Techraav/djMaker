<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $redirectPath = '/home';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleFacebookProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user, 'facebook');

        Auth::login($authUser);
        
        Flash::success('Vous êtes maintenant connecté !');
        return redirect('/');
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleGoogleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('auth/google');
        }

        if(isset($user->id))
        {
            Auth::user()->google_id = $user->id;
            Auth::user()->google_email = $user->email;
            Auth::user()->google_token = $user->token;
            Auth::user()->save();
        }    
        
        Flash::success('Votre compte Google+ est maintenant lié !');
        return redirect('/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $socialUser
     * @return User
     */
    private function findOrCreateUser($socialUser)
    {
        $authUser = User::where('facebook_id', $socialUser->id)->first();

        if ($authUser){
            return $authUser;
        }

        $user = User::where('email', $socialUser->email)->first();

        if($user)
        {
            Flash::error('Il existe déjà un compte à cette adresse email.');
            return redirect('login');
        }

        // $authUser = User::where('email', $socialUser->email)->first();

        // if(!empty($authUser))
        // {
        //     return redirect('auth/linkfromfacebook');
        // }

        return User::create([
            'first_name' => $socialUser->first_name,
            'last_name' => $socialUser->last_name,
            'email' => $socialUser->email,
            'facebook_id' => $socialUser->id,
            'avatar' => $socialUser->avatar
        ]);
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function register()
    {
        $validation = $this->validator(Input::all());

        if($validation->fails())
        {
            Flash::error('Inscription impossible. Veuillez vérifier les champs renseignés.');
            return Redirect::back()->withInput()->withErrors($validation->errors());
        }

        $user = User::where('email', Input::get('email'))->first();
        if($user){
            if($user->facebook_id != '')
            {
                Flash::error('Cette adress email est déja utilisée par un compte créé via Facebook.');
            }else
            {
                Flash::error('Cette adress email est déja utilisée.');
            }
            return Redirect::back()->withInput();
        }

        $email = Input::get('email');
        $users = User::where('email', $email)->get();


        if(count($users) > 1 )
        {
            Flash::error('Inscription impossible, cette adresse email est déjà utilisée.');
            return Redirect::back()->withInput();
        }
        elseif(count($users) == 1)
        {
            $userToLink = $users[0];
            if(str_slug($userToLink->first_name) == str_slug(Input::get('first_name')) && str_slug($userToLink->last_name) == str_slug(Input::get('last_name')))
            {
                return redirect('register?action=link')->withInput();
            }
        }

        $user = User::create([
                    'first_name' => ucfirst(Input::get('first_name')),
                    'last_name'  => ucfirst(Input::get('last_name')),
                    'email'      => Input::get('email'),
                    'password'   => bcrypt(Input::get('password')),
                    ]);

        Auth::login($user, true);

        if(Auth::guest())
        {
            Flash::error('Connexion impossible.');
            return redirect('login');
        }

        Flash::success('Bienvenue '.$user->name . ' !');
        return redirect('/');
    }

    public function login()
    {
        $validator = Validator::make(Input::all(), ['g-recaptcha-response' => 'required']);
        if($validator->fails())
        {
            Flash::error('Impossible de vous connecter : mauvais captcha.');
            return redirect('login');

        }
        if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')], true))
        {
            if(Auth::user()->banned == 0)
            {    
               Flash::success('Vous êtes maintenant connecté');
               return redirect('/');
            } else
            {
                Auth::logout();
                Flash::error('Impossible de vous connecter : votre compte a été banni. Pour plus d\'informations ou pour des réclamations, contactez un administrateur.');
                return redirect('/');
            }  
        }
        else
        {
            $error = true;
        }

        Flash::error('Impossible de vous connecter, veuillez réessayer');
        return redirect('login');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255',
            'password'   => 'required|confirmed',
            // 'g-recaptcha-response'  => 'required'
        ]);
    }

    public function googlePlus()
    {
        return view('auth.googleplus');
    }

    public function logout()
    {
        Auth::logout();
        Flash::success('Vous êtes maintenant déconnecté.');
        return redirect('/');
    }

    // public function linkAccounts(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();
    //     $user->password = $request->password;
    //     $user->save();

    //     if(Auth::guest())
    //     {
    //         Auth::login($user, true);
    //         Flash::success('Vos comptes ont été liés avec succès, et vous êtes maintenant connecté !');
    //         return redirect('/');
    //     }

    //     Flash::success('Vos comptes ont été liés avec succès.');
    //     return redirect('login');
    // }

    // public function linkToBasicAccount(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();
    //     $user->facebook_id = $request->facebook_id;
    //     $user->save();

    //     if(Auth::guest())
    //     {
    //         Auth::login($user, true);
    //         Flash::success('Vos comptes ont été liés avec succès, et vous êtes maintenant connecté !');
    //         return redirect('/');
    //     }

    //     Flash::success('Vos comptes ont été liés avec succès.');
    //     return redirect('login');
    // }
}
