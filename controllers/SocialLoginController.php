<?php

namespace App\Http\Controllers;

use Socialite;
use Auth;
use App\User;

class SocialLoginController extends Controller
{
     public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
       
        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('auth/'.$provider);
        }

        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);

        return redirect("/");
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($user)
    {
        $authUser = User::where('email', $user->email)->first();

        if ($authUser){
            $authUser->logo = $user->avatar;
            $authUser->save();
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'social_id' => $user->id,
            'logo' => $user->avatar
        ]);
    }
}