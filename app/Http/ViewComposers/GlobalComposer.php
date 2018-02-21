<?php namespace App\Http\ViewComposers;

use Auth;
use App\User;
use Illuminate\View\View;
//use App\Repositories\UserRepository;
use App\Traits\HelperTrait;

class GlobalComposer
{
    use HelperTrait;
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $user;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository $users
     * @return void
     */
    public function __construct() {
        // Dependencies automatically resolved by service container...
        //$this->user = $user;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view) {
    }
}