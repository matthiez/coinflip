<div class='row'>
    <div class='col-md-6'>
        <h3>Create a new Coinflip</h3>
        <ul>
            <li>Choose Terror/Anti-Terror</li>
            <li>Insert an Amount</li>
            <li>Press Button 'Create Coinflip'</li>
        </ul>
    </div>
    <div class='col-md-6'>
        <div class='text-center'>
            <img src="{{asset('img/terror.png')}}" class='coin-side selected' data-side='terror' width='128' height='128' alt='Terror' />
            <img src="{{asset('img/anti.png')}}" class='coin-side' data-side='anti' width='128' height='128' alt='Anti Terror' />
        </div>
        <input id="new_coinflip_value" type='number' min='1' max='999999' autocomplete="off" placeholder="Coinflip Value" title='Coinflip Value' class="form-control text-center" />
        <button id='create_coinflip' type='submit' class='btn btn-success btn-block'>Create Coinflip!</button>
    </div>
</div>
<hr>