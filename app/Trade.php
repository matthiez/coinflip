<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Trade
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $type
 * @property int $trade_offer_id
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Trade whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Trade whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Trade whereTradeOfferId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Trade whereType($value)
 */
class Trade extends Model
{
    //
}
