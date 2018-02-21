<?php namespace App\Http\Controllers;

use App\Traits\HelperTrait;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use HelperTrait;

    public function auth() {
        if (in_array($this->user['steamid'], $this->banned)) {
            $this->arr['error'] = 'You are banned from using this Site!';
        }
        else {
            $this->arr['success'] = true;
            $this->arr['steamid'] = (string)$this->user['steamid'];
        }
        return response()->json($this->arr);
    }

    public function getToken() {
        if (in_array($this->user['steamid'], $this->banned)) {
            return false;
        }
        else {
            return response()->json($this->user['token'], 200);
        }
    }
}