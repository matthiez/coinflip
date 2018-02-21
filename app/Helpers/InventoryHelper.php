<?php namespace App\Helpers;

use Log;
use Invisnik\LaravelSteamInventory\SteamInventory;

class InventoryHelper extends SteamInventory
{

    const APP_ID = 730;
    const CONTEXT_ID = 2;

    private $data = [
        'error' => 'Undefined error. Contact an admin.',
    ];

    public function get($steamid) {
        if ($this->validator($steamid)) {
            $inventory = $this->loadInventory($steamid, self::APP_ID, self::CONTEXT_ID);
            if ($inventory) {
                if (isset($inventory->currentData)) {
                    if ($inventory->currentData['success']) {
                        if (empty($inventory->currentData['rgDescriptions'])) {
                            $this->data['error'] = 'Inventory is empty.';
                        }
                        else {
                            $this->data = $inventory->currentData;
                        }
                    }
                    else {
                        $this->data['error'] = isset($inventory->currentData['error']) ? $inventory->currentData['error'] : 'SteamAPI cooldown or outage.';
                    }
                }
            }
        }
        return $this->data;
    }

    private function validator($steamid) {
        if (!is_numeric($steamid)) {
            $this->data['error'] = 'The SteamID not numeric.';
            return false;
        }
        if (!strlen($steamid) == 17) {
            $this->data['error'] = 'The SteamID has the wrong length.';
            return false;
        }
        return true;
    }

}