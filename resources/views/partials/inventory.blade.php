@foreach ($rgInventory as $k => $v)
    @php
        $_ = $v['classid'].'_'.$v['instanceid'];
        $rarity = App\Traits\InventoryTrait::getItemRarity($rgDescriptions[$_]['type']);
        $value = App\Price::where('market_hash_name', '=', $rgDescriptions[$_]['market_hash_name'])->select('price')->first();
        if (!is_int($value)) { $value = 0; }
    @endphp
    @if ($value === 2424)
        <div class="col-md-2 col-lg-2 item-container" data-assetid="{!! $k !!}">
            <div class="text-center">Item not accepted</div>
        </div>
    @else
        @if ($k !== 5305059322)
            <div class='col-lg-2 item-container' data-value='{!! $value !!}' data-assetid='{!! $k !!}'>
                <div class='rarity {!! $rarity !!}'></div>
                <div class='helper'>
                    <span class='value'>{!! $value !!}</span>
                    <span class="glyphicon glyphicon-bitcoin" aria-hidden="true"></span>
                </div>
                <div class='thumb'>
                    <img src='https://steamcommunity-a.akamaihd.net/economy/image/{!! $rgDescriptions[$_]['icon_url'] !!}/150fx150f' alt='{!! $rgDescriptions[$_]['market_hash_name'] !!}' />
                </div>
                <div class='name text-center'>{!! $rgDescriptions[$_]['market_hash_name'] !!}</div>
            </div>
        @endif
    @endif
@endforeach
