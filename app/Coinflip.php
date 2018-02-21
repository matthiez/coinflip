<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Coinflip
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $value
 * @property string $side
 * @property int $challenger
 * @property int $opposer
 * @property string $result
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereChallenger($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereOpposer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereResult($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereSide($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Coinflip whereValue($value)
 */
class Coinflip extends Model
{
}
