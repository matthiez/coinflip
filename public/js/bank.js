'use strict';
(function (io, $) {

    const Bank = {
        inventory: '',
        items: [],
        itemsValue: 0
    };

    const Socket = io(':3000/bank', {
        rejectUnauthorized: true,
        secure: true,
        port: 3000,
        reconnect: true
    });

    $(document).ready(function () {
        const $this = $(this);

        const Ui = {
            cashier: $this.find('#cashier').find('.panel-body'),
            inventory: $this.find('#inventory'),
            toolbar: $this.find('#toolbar'),
            selected: $this.find('#selected')
        };

        Ui.cashier.parent().hide();

        $this.find("#inventories").on('change', function selectInventory() {
            const _$this = $(this);
            Bank.inventory = _$this.val();
            $.post('withdraw/getInventory', {
                inventory: Bank.inventory
            }).done(function (data) {
                Ui.inventory.html(data.html);
                Ui.cashier.prepend(Help.currentTime(Settings['timezone']) + ` Successfully loaded Inventory from ` + _$this.find('option:selected').text() + `<br>`);
                Ui.inventory.html($(".item-container").sort(function (b, a) {
                    return $(a).data('value') - $(b).data('value');
                }));
            }).always(function () {
                Ui.cashier.parent().show();
                _$this.val('default');
            });
        });

        $this.on('click', '.item-container', function itemClicker() {
            const _$this = $(this);
            const assetid = _$this.data('assetid');
            const value = _$this.data('value');

            _$this.toggleClass('selected');
            if (_$this.hasClass('selected')) {}
            if (Bank.items.includes(assetid)) {
                Bank.items.splice(Bank.items.indexOf(assetid), 1);
                Bank.itemsValue -= parseInt(value);
            }
            else {
                Bank.items.push(assetid);
                Bank.itemsValue += parseInt(value);
            }
            const length = Bank.items.length;
            if (length < 1) {
                Ui.toolbar.hide();
                Ui.selected.html();
                return;
            }
            Ui.selected.html(`<b>${length} ${length === 1 ? 'item' : 'items'} selected with a total Value of ${Bank.itemsValue} Credits</b>`);
            Ui.toolbar.show();
        });

        $this.find('#deposit, #withdraw').on('click', function withdraw() {
            const $_this = $(this);
            const withdrawal = Bank.inventory.length;
            $.post(withdrawal ? 'withdraw/start' : 'deposit/start', {
                items: Bank.items,
                inventory: withdrawal ? Bank.inventory : null
            }).done(function (data) {
                Ui.cashier.prepend(Help.currentTime(Settings['timezone']) + ` ${withdrawal ? 'Withdrawal' : 'Deposit'} for ${data.steamid} with a Value of ${data.itemsValue} Credits has started.<br>`);
                Socket.emit(withdrawal ? 'withdrawal' : 'deposit', data);
            }).always(function () {
                $this.find('html, body').animate({
                    scrollTop: 0
                }, 0);
                $('.item-container').each(function () {
                    $(this).removeClass('selected');
                });
                Bank.items = [];
                Bank.itemsValue = 0;
                Ui.selected.empty();
                Ui.toolbar.hide();
            });
        });

        Socket.on('connect', function () {
            this.on('offer_sent', function (data) {
                Push.info('Offer #' + data.tradeId + ' sent to you, waiting for your answer.');
            });

            this.on('offer_done', function (data) {
                Push.success('Trade done! Your new balance: ' + data.response.Balance + ' chips<br>Thanks for using csgocards.');
            });

            this.on('offer_fail', function (data) {
                Push.error((data.error) ? data.error : data, 'error');
            });

            this.on('offer_transfer_fail', function () {
                Push.error('Aborting. Transfer failed.');
            });

            this.on('offer_countered', function () {
                Push.error('Aborting. Please do not send counter offers but make a new one instead.');
            });

            this.on('offer_declined', function () {
                Push.error('Aborting, because you declined the tradeoffer.');
            });

            this.on('offer_probation', function () {
                Push.error('Aborting, because you are affected by trade probation.');
            });

            this.on('offer_escrow', function () {
                Push.info(`Aborting Trade because you are affected by trade holds. <a href="https://support.steampowered.com/kb_article.php?ref=8078-TPHC-6195" target="_blank" title="Steam: All about trade holds">More info.</a>`);
            });
        });
    });
})(window.io, window.jQuery || window.$);