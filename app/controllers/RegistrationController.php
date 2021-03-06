<?php

/**
 * Controller na registráciu užívateľa
 */
class RegistrationController extends BaseController {

    /**
     * Zobrazí registračný formulár
     * @return type
     */
    public function getRegister() {
        return View::make('register');
    }

    /**
     * Pokúsi sa zaregistrovať užívateľa
     * @return type
     */
    public function postRegister() {
        $input = Input::only(
                        'name', 'surname', 'password', 'password_confirmation', 'email'
        );
        $rules = array(
            'name' => 'required',
            'surname' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'email' => 'required|email|unique:users,email',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $confirmation_code = str_random(30);

        User::create(array(
                    'name' => Input::get('name'),
                    'surname' => Input::get('surname'),
                    'email' => Input::get('email'),
                    'password' => Hash::make(Input::get('password')),
                    // 'confirmation_code' => $confirmation_code,
                    //TODO: odstranit a nechat mail
                    'confirmed' => 1
                ))->id;

        /* Mail::send('emails.auth.verify', array('confirmation_code' => $confirmation_code), function($message) {
          $message->to(Input::get('email'), Input::get('name'))
          ->subject(Lang::get('emails.verify_email'));
          }); */
        return Redirect::action('HomeController@showWelcome')
                        ->with('message', Lang::get('Registrácia prebehla úspešne'));
    }

    /**
     * Potvrdí registráciu užívateľa
     * @param type $confirmation_code
     * @return type
     * @throws InvalidConfirmationCodeException
     */
    public function confirm($confirmation_code = null) {
        if (!$confirmation_code) {
            return Redirect::action('HomeController@showWelcome')
                            ->with('warning', Lang::get('common.acces_denied'));
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user) {
            throw new InvalidConfirmationCodeException;
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        return Redirect::action('HomeController@showWelcome')
                        ->with('message', Lang::get('common.email_verified'));
    }

}
