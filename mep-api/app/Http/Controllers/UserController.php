<?php

namespace App\Http\Controllers;

use App\Http\Services\CMDBService as CMDBService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

class UserController extends Controller
{

    public function redirect()
    {
    	return Socialite::driver('keycloak')->redirect();
    }

    public function callback()
    {
        $userSocial = Socialite::driver('keycloak')->user();
        $user = User::where(['name' => $userSocial->getName()])->first();
        $email = $userSocial->getEmail();
        $otrsLogin = $email ? $this->getOTRSLogin($email) : null;

        if (! $user) {
            $user = new User;
            $user->name = $userSocial->getName();
            $user->email = $userSocial->getEmail();
            $user->password = '';
        }
        $user->otrs_login = $otrsLogin;
        $user->save();

        Auth::login($user);

        return redirect('/');
    }

    // Ermittelt die anhand der E-Mail-Adresse des angemeldeten Benutzers
    // den OTRSLogin aus der CMDB, damit derjenige Tickets erstellen kann.
    private function getOTRSLogin(string $email)
    {
        $cmdb = new CMDBService();
        $otrsLogins = $cmdb->getOTRSLoginFromCMDB($email);

        // Da es vorkommen kann, das zu einer Mail mehrere Logins mit unterschiedlichen
        // Kunden existiert, wird hier nochmal nach dem Kunden gefiltert.
        foreach ($otrsLogins['payload']['data'] as $key => $otrsLogin) {
            if ($otrsLogin['USER_customer_id'] == getenv('CUSTOMER')) {
                return $otrsLogin['USER_login'];
            }
        }

        return '';
    }
}
