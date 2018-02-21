<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use Auth;
use Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Invisnik\LaravelSteamAuth\SteamAuth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/coinflip';

    private $steam;

    public function __construct(SteamAuth $steam) {
        $this->middleware('guest', ['except' => 'logout']);
        $this->steam = $steam;
    }

    public function steamLogin() {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = User::where('steamid', '=', $info->steamID64)->first();
                $ip = \Request::ip();
                $token = str_random(128);

                $new = is_null($user) ? true : false;
                if (is_null($user)) {
                    $user = User::create([
                        'steamid' => $info->steamID64,
                        'steam_avatar' => $info->avatarfull,
                        'steam_name' => $info->personaname,
                        'ip' => $ip,
                        'token' => $token,
                    ]);
                    Setting::create([
                        'steamid' => $info->steamID64,
                    ]);
                }
                else {
                    $user->update([
                        'steam_avatar' => $info->avatarfull,
                        'steam_name' => $info->personaname,
                        'ip' => $ip,
                        'token' => $token,
                    ]);
                }
                Auth::login($user, true);
                Cookie::queue('token', $token, time() + 3600 * 24 * 7, '/', '', true, false);
                return redirect()->route('home')->with('success', $new ? "Hello and welcome $info->personaname. Enjoy your Stay!" : "Welcome back $info->personaname. Have a nice Day!");
            }
        }
        return $this->steam->redirect(); // redirect to Steam login page
    }
}
