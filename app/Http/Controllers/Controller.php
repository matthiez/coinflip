<?php namespace App\Http\Controllers;

use Auth;
use Log;
use App\Traits\HelperTrait;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use HelperTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $dbErr = 'Internal Server Error. Contact an Admin.';

    public $banned = [];
    public $arr = [
        'success' => false
    ];

    public $request;
    public $user;
    public $settings;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::check() ? User::where('steamid', '=', Auth::user()->steamid)->first()->toArray() : null;
            $this->settings = Auth::check() ? Setting::where('steamid', '=', Auth::user()->steamid)->first()->toArray() : null;
            $this->request = new Request;
            return $next($request);
        });
    }

    public function debug($var) {
        Log::debug(print_r($var, true));
    }
}
