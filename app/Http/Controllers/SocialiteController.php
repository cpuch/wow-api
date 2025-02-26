<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Battlenet authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('battlenet')
            ->scopes(['wow.profile'])
            ->redirect();
    }

    /**
     * Obtain the user information from Battlenet.
     */
    public function callback()
    {
        $battlenetUser = Socialite::driver('battlenet')->user();

        $token = $battlenetUser->token;

        $user = User::updateOrCreate([
            'battlenet_id' => $battlenetUser->id,
        ], [
            'name' => $battlenetUser->nickname,
            'battlenet_token' => $battlenetUser->token,
        ]);

        Auth::login($user, true); // Remember the user

        return redirect()->route('dashboard');
    }
}
