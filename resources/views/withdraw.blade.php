@extends('master')
@section('head')
    <title>Withdraw - {{ config('app.name', 'Coinflip') }}</title>
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
    </style>
@endsection
@section('content')
    <div class='container'>
        <h1>Withdraw Items</h1>
        <div class='form-group'>
            <label for="inventories" class='label label-default'>Which inventory do you want to load?</label>
            <select id="inventories" class="form-control">
                <option value="default" disabled selected>Select an Inventory</option>
                @foreach($bots as $key => $value)
                    <option value='{!! $key !!}'>{!! $value['name'] !!}</option>
                @endforeach
            </select>
        </div>
        <div id='cashier' class="panel panel-default">
            <a href='#' title='Close' onclick='$(this).parent().hide();' class='btn pull-right'><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            <div class="panel-heading"><h3 class="panel-title">Status Window</h3></div>
            <div class="panel-body">{{-- JS/NODE --}}</div>
        </div>
        <div id="inventory"></div>
        <div id="toolbar" class='text-center'>
            <div id="selected"></div>
            <button id='withdraw' type='button' class='btn'>Withdraw Items</button>
        </div>
    </div>
@endsection
@section('js')
    <script src='{!! asset('js/bank.js') !!}'></script>
@endsection