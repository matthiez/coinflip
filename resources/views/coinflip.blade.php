@extends('master')
@section('head')
    <title>{{ config('app.name', 'Coinflip') }}</title>
@endsection
@section('content')
    <div class='container-fluid'>
        <div id='new_coinflip'>
            @include ('partials.newCoinflip')
        </div>
        <div id='coinflip'>
            <div class='row'>
                <div class='text-center'>
                    <b>Coinflip #<span id='current_coinflip_id'>{{--Node--}}</span></b><br>
                    Value: <span id='current_coinflip_value'>{{--Node--}}</span> Chips
                </div>
                <div id='coinflip_container'>
                    <div id='coin'>
                        <div class='front'></div>
                        <div class='back'></div>
                    </div>
                </div>
            </div>
        </div>
        <div id='coinflips'>
            <div class='table-responsive'>
                <table class='table table-hover'>
                    <thead class='thead-inverse'>
                    <tr>
                        <th>Challenger</th>
                        <th>Opposer</th>
                        <th>Value</th>
                        <th>Side</th>
                        <th>Watch</th>
                        <th>Winner</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class='hide' data-id='0'>
                        <td class='challenger'></td>
                        <td class='opposer'></td>
                        <td class='value'></td>
                        <td class='side'></td>
                        <td class='watch'><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></td>
                        <td class='status'><button type='button' class='btn'></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src='{!! asset('js/coinflip.js') !!}'></script>
@endsection