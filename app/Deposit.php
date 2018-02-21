<?php namespace App;

use App\Price;
use App\Traits\InventoryTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Deposit
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $steamid
 * @property string $tradelink
 * @property string $items
 * @property string $item_names
 * @property int $items_value
 * @property string $player
 * @property int $trade_offer_id
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereItemNames($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereItems($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereItemsValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit wherePlayer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereSteamid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereTradeOfferId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereTradelink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Deposit whereUpdatedAt($value)
 */
class Deposit extends Model
{
    use InventoryTrait;

    public function calculateItemsValue($itemNames) {
        $itemsValue = 0;
        if (is_array($itemNames)) {
            foreach ($itemNames as $marketHashName) {
                $itemsValue += Price::where('market_hash_name', '=', $marketHashName)->select('price')->first();
            }
        }
        return $itemsValue;
    }

    public function depositItems($items, $steamid, $player, $tradeLink) {
        /* Get Market Hash Names from AssetIDs */
        $itemNames = $this::getMarketHashNames($steamid, $items);

        /* Calculate Total Items Value from Market Hash Names */
        $itemsValue = $this->calculateItemsValue($itemNames);
        if ($itemsValue == 0) {
            return ["success" => false, "error" => "Aborting because the total item value could not be calculated. Please contact an admin."];
        }

        /* Add Deposit to Database */
        $deposit = new Deposit;
        $deposit->steamid = $steamid;
        $deposit->trade_link = $tradeLink;
        $deposit->items = json_encode($items);
        $deposit->item_names = json_encode($itemNames);
        $deposit->items_value = $itemsValue;
        $deposit->player = $player;
        $deposit->status = 'Waiting for Socket.IO';
        if ($deposit->save() != 0) {
            \Log::debug('model::Deposit->depositItems()' . PHP_EOL . print_r($deposit, true));
            if (is_int($deposit->id)) {
                return [
                    "success" => true,
                    "id" => $deposit->id,
                    "itemNames" => $deposit->item_names,
                    "items" => $deposit->items,
                    "itemsValue" => $deposit->items_value,
                    "steamid" => $deposit->steamid,
                    "tradeLink" => $deposit->trade_link
                ];
            }
            \Log::error('model::Deposit->depositItems().$id' . PHP_EOL . print_r($deposit, true));
            return ["success" => false, "error" => "Error getting latest Deposit-ID."];
        }
        \Log::error('model::Deposit->depositItems().$deposit' . PHP_EOL . print_r($deposit, true));
        return ["success" => false, "error" => "Error when saving Deposit to Database."];
    }

    public function amountDeposited($steamid) {
        $amountDeposited = 0;
        $deposits = Deposit::where([['steamid', '=', $steamid], ['status', '=', 'DONE']])->select(['items_value'])->get();
        foreach ($deposits as $deposit) {
            $amountDeposited = $amountDeposited + $deposit->items_value;
        }
        return $amountDeposited;
    }

    public function timesDeposited($steamid) {
        return Deposit::where([['steamid', '=', $steamid], ['status', '=', 'DONE']])->count();
    }

}
