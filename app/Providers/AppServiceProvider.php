<?php namespace App\Providers;

use Auth;
use App\Coinflip;
use App\Deposit;
use App\Withdrawal;
use App\Setting;
use App\User;
use App\Helpers\InventoryHelper;
use App\Traits\HelperTrait;
use App\Traits\InventoryTrait;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    use HelperTrait, InventoryTrait;

    protected $coinflip;
    protected $user;
    protected $deposit;
    protected $withdrawal;

    public function __construct(Application $app) {
        parent::__construct($app);
        $this->coinflip = new Coinflip();
        $this->user = new User();
        $this->deposit = new Deposit();
        $this->withdrawal = new Withdrawal();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        /*      View::composer(
                  '*', 'App\Http\ViewComposers\GlobalComposer'
              );*/

        View::composer('*', function ($view) {
            if (Auth::guest()) {
                $view->with([]);
            }
            else {
                $steamid = Auth::user()->steamid;
                $user = User::where('steamid', '=', $steamid)->first()->toArray();
                foreach ($user as $key => $value) {
                    $user[$key] = e($value);
                }
                $view->with([
                    'user' => $user,
                    'settings' => Setting::where('steamid', '=', $steamid)->first()->toArray()
                ]);
            }
        });

        View::composer('deposit', function ($view) {
            $inventory = (new InventoryHelper)->get(Auth::user()->steamid);
            $view->rgInventory = $inventory['rgInventory'];
            $view->rgDescriptions = $inventory['rgDescriptions'];
        });

        View::composer('withdraw', function ($view) {
            $view->bots = $this->bots;
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
