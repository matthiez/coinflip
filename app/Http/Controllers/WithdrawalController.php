<?php namespace App\Http\Controllers;

use Log;
use App\Price;
use App\Withdrawal;
use App\Helpers\InventoryHelper;
use App\Traits\InventoryTrait;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{

    use InventoryTrait;

    private $data = [];

    private function send() {
        return response()->json($this->data, isset($this->data['error']) ? 400 : 200);
    }

    public function getInventory(Request $request) {
        $this->validate($request, [
            'inventory' => 'required|int'
        ]);
        $id = $request->input('inventory');
        if (!is_array($this->bots[$id])) {
            $this->data['error'] = 'Unknown Inventory.';
        }
        else {
            $inventory = (array)(new InventoryHelper())->get($this->bots[$id]['steamid']);
            if (isset($inventory['error'])) {
                $this->data['error'] = $inventory['error'];
            }
            else {
                $this->data['html'] = view('partials.inventory')->with(['rgInventory' => $inventory['rgInventory'], 'rgDescriptions' => $inventory['rgDescriptions']])->render();
            }
        }
        return $this->send();
    }

    public function start(Request $request) {
        $items = $request->input('items');
        $id = $request->input('inventory');

        $this->validate($request, [
            'inventory' => 'required|int',
            'items' => 'required|array'
        ]);

        if (!is_array($this->bots[$id])) {
            $this->data['error'] = 'Unknown Inventory.';
        }

        /* Tradelink existing? */
        if (empty($this->settings['tradelink'])) {
            $this->data['error'] = 'Aborting because your tradelink could not be found. Did you set one up?';
        }

        /* Calculate Items Total Value */
        $itemNames = $this::getMarketHashNames((array)(new InventoryHelper())->get($this->bots[$id]['steamid']), $items);
        $itemsValue = 0;
        foreach ($itemNames as $marketHashName) {
            $itemsValue += Price::where('market_hash_name', '=', $marketHashName)->select('price')->first();
        }
        if ($itemsValue === 0) {
            $this->data['error'] = 'Aborting because the total item value could not be calculated. Please try again.'; /////////////// DEBUG
        }

        /* Check Player Balance */
        if ($this->user['balance'] < $itemsValue) {
            $this->return['error'] = 'Aborting because your balance is not high enough to withdraw these items.'; /////////////// DEBUG
        }

        /* Save to DB */
        if (!isset($this->data['error'])) {
            $withdrawal = new Withdrawal;
            $withdrawal->tradelink = $this->settings['tradelink'];
            $withdrawal->steamid = $this->user['steamid'];
            $withdrawal->inventory_steamid = $this->bots[$id]['steamid'];
            $withdrawal->items = json_encode($items);
            $withdrawal->item_names = json_encode($itemNames);
            $withdrawal->items_value = $itemsValue;
            $withdrawal->player = $this->user['steam_name'];
            $withdrawal->trade_offer_id = null;
            $withdrawal->status = 'Waiting for Socket.IO';
            if ($withdrawal->save()) {
                $this->data['success'] = true;
                $this->data['id'] = $withdrawal->id;
                $this->data['itemNames'] = $withdrawal->item_names;
                $this->data['items'] = e($withdrawal->items);
                $this->data['itemsValue'] = $withdrawal->items_value;
                $this->data['steamid'] = (string)$withdrawal->steamid;
                $this->data['tradeLink'] = e($withdrawal->tradelink);
            }
            else {
                $this->return['error'] = 'Internal Server Error.';
            }
        }
        return $this->send();
    }

}