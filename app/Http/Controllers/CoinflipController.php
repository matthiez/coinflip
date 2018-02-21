<?php namespace App\Http\Controllers;

use Auth;
use Log;
use App\Coinflip;
use App\User;
use App\Traits\CoinflipTrait;
use Illuminate\Http\Request;

class CoinflipController extends Controller
{

    use CoinflipTrait;

    private $data = [];

    private function send() {
        return response()->json($this->data, isset($this->data['error']) ? 400 : 200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'value' => 'required|integer|between:1,999999',
            'side' => 'required|string',
        ]);
        $value = $request->input('value');
        $side = $request->input('side');
        $this->checkSide($side);
        $this->checkBalance($value, $this->user['balance']);
        if (!isset($this->data['error'])) {
            $coinflip = new Coinflip;
            $coinflip->value = $value;
            $coinflip->side = $side;
            $coinflip->challenger = $this->user['steamid'];
            if ($coinflip->save()) {
                $challenger = User::where('steamid', '=', $this->user['steamid'])->first();
                $challenger->balance = ($challenger->balance - $coinflip->value);
                if ($challenger->save()) {
                    $this->data['success'] = true;
                    $this->data['id'] = $coinflip->id;
                    $this->data['steamid'] = (string)$coinflip->challenger;
                }
                else $this->data['error'] = $this->dbErr;
            }
            else $this->data['error'] = $this->dbErr;
        }
        return $this->send();
    }

    public function join(Request $request) {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);
        $id = $request->input('id');

        $coinflip = Coinflip::where('id', '=', $id)->get()[0];

        $this->checkResult($coinflip->result);
        $this->checkBalance($coinflip->value, $this->user['balance']);

        if (!isset($this->data['error'])) {
            $coinflip = Coinflip::where('id', '=', $id)->first();
            $coinflip->opposer = $this->user['steamid'];
            if (!$coinflip->save()) {
                $this->arr['error'] = $this->dbErr;
            }
            else {
                $opposer = User::where('steamid', '=', $this->user['steamid'])->first();
                $opposer->balance = ($opposer->balance - $coinflip->value);
                if ($opposer->save()) {
                    $this->data['success'] = true;
                    $this->data['id'] = $coinflip->id;
                    $this->data['steamid'] = (string)$this->user['steamid'];
                }
                else {
                    $this->data['error'] = $this->dbErr;
                }
            }
        }
        return $this->send();
    }
}