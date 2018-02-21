<?php namespace App\Traits;

trait CoinflipTrait
{

    public $missingCoins = 'You do not have enough Coins.';

    public function checkSide($side) {
        if ($side !== 'terror' && $side !== 'anti') $this->data['error'] = 'Invalid Side Choice.';
    }

    public function checkResult($result) {
        if (!is_null($result)) $this->data['error'] = 'Coinflip has already being played.';
    }

    public function checkBalance($value, $balance) {
        if ($value > $balance) $this->data['error'] = $this->missingCoins;
    }
}