<?php

return [

    /*
     * Redirect URL after login
     */
    'redirect_url' => '/steamlogin',
    /*
     * API Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => env('STEAM_API_KEY'),
    /*
     * Is using https ?
     */
    'https' => true

];
