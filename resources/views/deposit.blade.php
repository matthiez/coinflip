@extends('master')
@section('head')
    <title>Deposit - {{ config('app.name', 'Coinflip') }}</title>
    <style>
        #toolbar {
            display:          none;
            position:         fixed;
            bottom:           0;
            left:             0;
            width: 100%;
            background-color: rgb(247, 247, 249);
        }
        #cashier {
            overflow: auto;
            background-color: #f7f7f9;
        }
        #reload {
            position: fixed;
            top: 50%;
            left: -24px;
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            transform: rotate(270deg)
        }
    </style>
@endsection
@section ('content')
    <div class='container'>
        <div id='inventory-value' class='text-center'>{{-- JS --}}</div>
        <button id='reload' type='button' class='btn btn-info' onclick='location.reload()'>Reload</button>
        <h1>Deposit Items</h1>
        <b>Only Counter-Strike:Global Offensive items accepted.</b>
        <div id='cashier' class="panel panel-default">
            <a href='#' title='Close' onclick='$(this).parent().hide();' class='btn pull-right'><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            <div class="panel-heading"><h3 class="panel-title">Status Window</h3></div>
            <div class="panel-body">{{-- JS/NODE --}}</div>
        </div>
        @if (isset($rgInventory['error']) || isset($rgDescriptions['error']))
            {!! isset($rgInventory['error']) ? $rgInventory['error'] : $rgDescriptions['error'] !!}<br>
            <a href='https://support.steampowered.com/kb_article.php?ref=4113-YUDH-6401' target='_blank'>Inventory private?</a>
        @else
            <div id="inventory">
                @include ('partials.inventory')
            </div>
            <div id="toolbar" class='text-center'>
                <div id="selected"></div>
                <button id='deposit' type='button' class='btn'>Withdraw Items</button>
            </div>
        @endif
    </div>
@endsection
@section('js')
    <script src='{!! asset('js/bank.js') !!}'></script>
@endsection