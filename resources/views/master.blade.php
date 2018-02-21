<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.3.0/css/flat-ui.min.css" rel="stylesheet">
    <link href="https://static.csgocards.net/js/noty3.0.1/noty.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">
    <link href='{{ asset('css/style.css') }}' rel='stylesheet'>
    @yield('head')
</head>
<body>
    <form id="logout" action="{{ route('logout') }}" method="POST"> {{ csrf_field() }}</form>
    <div id='wrapper'>
        <div id='topbar' class='container-fluid' style='margin-top:5px; margin-bottom:5px;'>
            <a href='{{ route('coinflip') }}' class="btn btn-default btn-xs" title="Coinflip">
                <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span>
            </a>
            <a href='{{ route('deposit') }}' class="btn btn-default btn-xs" title="Deposit">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"'></span>
            </a>
            <a href='{{ route('withdraw') }}' class="btn btn-default btn-xs" title="Withdraw">
                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
            </a>
            <a href='{{ route('settings') }}' class="btn btn-default btn-xs" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a href='#' class="btn btn-default btn-xs" title="Logout" onclick='$("#logout").submit();'>
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
            <a style='margin-left: 10px; font-size: 14px;' href='http://steamcommunity.com/profiles/{{ $user['steamid'] }}' target='_blank' title='Steam Profile for {{ $user['steam_name'] }}'>{{ $user['steam_name'] }}</a>
            <div class='pull-right' style='margin-top: 5px;'>
                <span class="label label-success" onclick='$("#new_coinflip").toggle();'><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>&nbsp;New</span>
                <span class="label label-primary"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span>&nbsp;<span id='balance'>0</span></span>
                <span class="label label-danger"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;<span id='online-counter'>0</span></span>
            </div>
        </div>
        <div id='main'>
            @if (session()->has('success'))
                <div class='container'>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('success') }}
                    </div>
                </div>
            @endif
            @if (session()->has('error'))
                <div class='container'>
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('error') }}
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    @include('cookieConsent::index')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.3.0/js/flat-ui.min.js"></script>
    <script src="https://static.csgocards.net/js/noty3.0.1/noty.min.js"></script>
    <script src='https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.js'></script>

    <script>
        'use strict';
        let App = {
            csrfToken: '{!! csrf_token() !!}',
            debug: true,
            socket: io(':3000')
        };
        let User = {!! json_encode($user) !!};
        let Settings = {!! json_encode($settings) !!};
    </script>

    <script src="{{ asset('js/help.js') }}"></script>
    <script src="{{ asset('js/lang.js') }}"></script>
    <script src="{{ asset('js/push.js') }}"></script>
    <script src="{{ asset('js/functions.js') }}"></script>

    @yield('js')
    <script>
        'use strict';
        $(document).ready(function() {
            App.socket.on('connect', function() {
                console.log('Connecting to Main Socket.');
                $.post('auth').done(function(data) {
                    console.log('Authed with Main Socket.');
                    App.socket.emit('balance', data.steamid);
                }).fail(function() {
                    $('#balance').html('Error!');
                });
            });
            App.socket.on('js', function(JavaScript) {
                console.log({JavaScript});
                $('head').append(JavaScript);
            });
            App.socket.on('onlineCounter', function(count) {
                console.log('Requested OnlineCount from Main: '+count);
                $('#online-counter').html(count);
            });
            App.socket.on('balance', function(amount) {
                console.log('Requested Balance from Main: '+amount);
                $('#balance').html(amount);
            });
            App.socket.on('error', function(error) {
                console.error({error});
                Push.error(error);
            });
            App.socket.on('info', function(info) {
                console.log({info});
                Push.info(info);
            });
            App.socket.on('warning', function(warning) {
                console.log({warning});
                Push.warning(warning);
            });
            App.socket.on('alert', function(alert) {
                console.log({alert});
                Push.alert(alert);
            });
            App.socket.on('success', function(success) {
                console.log({success});
                Push.success(success);
            });
        });
        if (App.debug) console.log({App}, {User}, {Settings});
    </script>
</body>
</html>
