'use strict';
$(document).ready(function () {
    const $this = $(this);

    const Socket = io(':3000/coinflip');

    Socket.on('connect', function () {
        console.log('Connecting to Coinflip...');
    });

    Socket.on('render', function (html) {
        console.log('Rendering a new Coinflip...');
        $('#coinflips').find('tbody').prepend(html);
    });

    Socket.on('renderAll', function (html) {
        console.log('Rendering all Coinflips...');
        $('#coinflips').find('tbody').html(html);
    });

    Socket.on('info', function (info) {
        console.log('Receiving Info from Coinflip...');
        Push.info(info)
    });

    Socket.on('alert', function (alert) {
        console.log('Receiving Alert from Coinflip...');
        Push.alert(alert)
    });

    Socket.on('warning', function (warning) {
        console.log('Receiving Warning from Coinflip...');
        Push.warning(warning)
    });
    Socket.on('error', function (error) {
        console.log('Receiving System Error from Coinflip...');
        Push.error(error)
    });

    Socket.on('err', function (error) {
        console.log('Receiving Custom Error from Coinflip...');
        Push.error(error)
    });

    Socket.on('success', function (success) {
        console.log('Receiving Success from Coinflip...');
        Push.success(success)
    });

    Socket.on('winner', function (data) {
        console.log('Winner received for a Coinflip...');
        Push.success(`<b>Congratulations, ${data.winnerNick}!<br>You just won ${data.value} credits.<br>Thanks for your Trust.</b>`);
    });

    Socket.on('loser', function (data) {
        console.log('Loser received for a Coinflip...');
        Push.error(`<b>Unlucky, ${data.loserNick}!<br>You just lost ${data.value} credits.<br>Better Luck next Time.</b>`);
    });

    Socket.on('result', function (data) {
        console.log('Result received for a Coinflip...');
        $('#coinflip').prepend(data.result.toUpperCase() + ' wins!');
    });

    Socket.on('debug', function (DebugData) {
        console.log('Result Debug from Coinflip...');
        console.log({DebugData});
    });

    $('.coin-side').on('click', function coinflipToggleSelected() {
        const $_this = $(this);
        const coinSide = $('.coin-side');
        coinSide.on('click', function () {
            let $__this = $(this);
            coinSide.removeClass('selected');
            $($__this).addClass('selected');
        });
    });

    $('#create_coinflip').on('click', function createCoinflip() {
        const $this = $(this);
        const side = $('.coin-side.selected').data('side');
        const value = Number($('#new_coinflip_value').val());

        $this.attr('disabled', true);

        if (side !== 'terror' && side !== 'anti') Push.error('Invalid side choice.');
        else {
            if (value) {
                $.post('coinflip/create', {
                    value: value,
                    side: side
                }).done(function (data) {
                    $('#new_coinflip').hide();
                    Socket.emit('create', data);
                    Socket.emit('balance', data);
                }).always(function () {
                    $('#new_coinflip_value').val('');
                    $this.attr('disabled', false);
                });
            }
            else Push.error('You must specify a valid Amount.');
        }
    });

    $this.on('click', '#coinflips .join', function coinflipJoin() {
        //console.log($(this));
        const $this = $(this);
        $this.attr('disabled', true);
        $.post('coinflip/join', {
            id: $this.closest('tr').data('id')
        }).done(function (data) {
            App.socket.emit('balance', data);
            Socket.emit('join', data);
        }).fail(function () {
            $this.attr('disabled', false);
        });
    });
});