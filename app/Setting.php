<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Setting
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $steamid
 * @property bool $confirm_big_bets
 * @property bool $enable_sounds
 * @property bool $in_valuta
 * @property string $language
 * @property bool $hide_profile_link
 * @property string $timezone
 * @property string $tradelink
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereConfirmBigBets($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereEnableSounds($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereHideProfileLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereInValuta($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereSteamid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereTradelink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Setting whereUpdatedAt($value)
 */
class Setting extends Model
{

    protected $fillable = ['steamid'];

    protected $hidden = ['id', 'steamid', 'created_at'];

}
