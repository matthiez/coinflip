<?php namespace App\Http\Controllers;

use App\Traits\HelperTrait;
use App\User;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    use HelperTrait;

    public function timezone(Request $request) {
        $this->validate($request, [
            'timezone' => 'required|string',
        ]);
        $timezone = $request->input('timezone');
        if (!HelperTrait::timezoneLabel($timezone)) {
            return response()->json(['success' => false, 'error' => 'Unknown Timezone.'], 400);
        }
        if ($timezone === $this->settings['timezone']) {
            return response()->json(['success' => false, 'error' => 'Aborting, because the selected timezone is equal to your current one.'], 400);
        }
        $setting = Setting::where('steamid', '=', $this->user['steamid'])->first();
        $setting->timezone = $timezone;
        if ($setting->save()) {
            return response()->json(['success' => true, 'timezone' => e($setting->timezone)], 200);
        }
        \Log::error('Error setting Timezone' . PHP_EOL . print_r($request->all(), 1));
        return response()->json(['success' => false, 'error' => 'Error. Please contact an admin.'], 400);
    }

    public function tradelink(Request $request) {
        $this->validate($request, [
            'tradelink' => 'required|url'
        ]);
        $tradelink = $request->input('tradelink');
        if (preg_match("/steamcommunity\.com\/tradeoffer\/new\/\?partner=[0-9]*&token=[a-zA-Z0-9_-]*/i", $tradelink) !== 1) {
            return response()->json(['success' => false, 'error' => 'Invalid Tradelink.'], 400);
        }
        if ($tradelink === $this->settings['tradelink']) {
            return response()->json(['success' => false, 'error' => 'Aborting, because the specified tradelink is equal to your current one.'], 400);
        }
        $setting = Setting::where('steamid', '=', $this->user['steamid'])->first();
        $setting->tradelink = $tradelink;
        if ($setting->save()) {
            return response()->json(['success' => true, 'tradelink' => e($setting->tradelink)]);
        }
        \Log::error('Error setting Tradelink' . PHP_EOL . print_r($request->all(), 1));
        return response()->json(['success' => false, 'error' => 'An error occured. Please contact an administrator.'], 400);
    }

}