<?php namespace App;

use App\Traits\InventoryTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Withdrawal
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $status
 * @property int $trade_offer_id
 * @property int $inventory_steamid
 * @property int $steamid
 * @property string $tradelink
 * @property string $items
 * @property string $item_names
 * @property int $items_value
 * @property string $player
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereInventorySteamid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereItemNames($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereItems($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereItemsValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal wherePlayer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereSteamid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereTradeOfferId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereTradelink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Withdrawal whereUpdatedAt($value)
 */
class Withdrawal extends Model
{
    use InventoryTrait;

    public function withdrawItems($steamid, $player, $items, $invSteamId, $tradeLink, $itemNames, $itemsValue) {
        /* Save to DB */
        $withdrawal = new Withdrawal;
        $withdrawal->trade_link = $tradeLink;
        $withdrawal->steamid = $steamid;
        $withdrawal->inventory_steamid = $invSteamId;
        $withdrawal->items = json_encode($items);
        $withdrawal->item_names = json_encode($itemNames);
        $withdrawal->items_value = $itemsValue;
        $withdrawal->player = $player;
        $withdrawal->trade_offer_id = null;
        $withdrawal->status = 'Waiting for Socket.IO';
        $save = $withdrawal->save();
        if ($save != 0) {
            \Log::debug('model::Withdrawal->withdrawItems()' . PHP_EOL . print_r($withdrawal, true));
            if (is_int($withdrawal->id)) {
                return [
                    "success" => true,
                    "id" => $withdrawal->id,
                    "itemNames" => $withdrawal->item_names,
                    "items" => $withdrawal->items,
                    "itemsValue" => $withdrawal->items_value,
                    "steamid" => $withdrawal->steamid,
                    "tradeLink" => $withdrawal->trade_link
                ];
            }
            return ["success" => false, "error" => "Database error."];
        }
        \Log::error('DB Error: ' . PHP_EOL . print_r($save, 1));
        return ["success" => false, "error" => "Database error."];
    }

    public function amountWithdrawn($steamid) {
        $amountWithdrawn = 0;
        $withdrawals = Withdrawal::where([['steamid', '=', $steamid], ['status', '=', 'DONE']])->select(['items_value'])->get();
        if (isset($withdrawals[0]->items_value)) {
            foreach ($withdrawals as $withdrawal) {
                $amountWithdrawn = $amountWithdrawn + $withdrawal->items_value;
            }
        }
        return $amountWithdrawn;
    }

    public function timesWithdrawn($steamid) {
        return Withdrawal::where([['steamid', '=', $steamid], ['status', '=', 'DONE']])->count();
    }

}
