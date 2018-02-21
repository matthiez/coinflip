'use strict';

(function ajaxSetup() {
    let _loadingContainer;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': App.csrfToken
        },
        beforeSend() {
            const elem = $(document.activeElement);
            if (elem.is('select') || elem.is('input[type="url"]')) {
                _loadingContainer = $('label[for=' + elem.attr('id') + ']');
                _loadingContainer.append(`<span style='margin-left: 5px; color: indianred;' class="glyphicon glyphicon-refresh" aria-hidden="true"></span>`).find('.glyphicon-refresh').addClass('glyphicon-spin');
            }
        },
        success(data, textStatus, jqXHR) {
        },
        error(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status !== 400 && jqXHR.status !== 422 && jqXHR.status !== 500) {
                let error = 'Undefined Error. Try again or contact an Admin.';
                if (jqXHR.error) {
                    if (typeof jqXHR.error === 'string' || jqXHR.error instanceof String) error = jqXHR.error;
                }
                else if (typeof jqXHR === 'string' || jqXHR instanceof String) error = jqXHR;
                new Noty({
                    type: 'error',
                    text: error
                }).show();
            }
        },
        complete(jqXHR, textStatus) {
            if (typeof(_loadingContainer) !== 'undefined') _loadingContainer.find('span').remove();
            if (App.debug) console.log({jqXHR, textStatus});
        },
        statusCode: {
            400(CustomValidator) {
                new Noty({
                    type: 'error',
                    text: CustomValidator.responseJSON['error']
                }).show();
            },
            422(AutoValidator) {
                let errors = '<ul>';
                $.each(JSON.parse(AutoValidator.responseText), function (key, value) {
                    errors += '<li>' + key + ': ' + value + '</li>';
                });
                errors += '</ul>';
                new Noty({
                    type: 'error',
                    text: errors
                }).show();
            },
            500() {
                new Noty({
                    type: 'error',
                    text: 'This was our Fault. Please try again later or contact an Admin. '
                }).show();
            }
        }
    });
})();

(function capitalizeFirst() {
    if (!String.prototype.capitalizeFirst)
        String.prototype.capitalizeFirst = () => this.charAt(0).toUpperCase() + this.slice(1);
})();


(function format() {
    if (!String.prototype.format)
        String.prototype.format = () => this.replace(/{(\d+)}/g, (match, number) => typeof arguments[number] === 'undefined' ? match : arguments[number]);
})();

(function str2int() {
    if (!String.prototype.str2int) {
        String.prototype.str2int = function () {
            let str = arguments[0];
            str = str.replace(/,/g, "");
            str = str.toLowerCase();
            let i = parseFloat(str);
            if (isNaN(i)) return 0;
            else if (str.charAt(str.length - 1) == "k") i *= 1000;
            else if (str.charAt(str.length - 1) == "m") i *= 1000000;
            else if (str.charAt(str.length - 1) == "b") i *= 1000000000;
            return i;
        };
    }
})();

(function noTradelink() {
    if (!Settings['tradelink'] && window.location.pathname !== "/settings") {
        return new Noty({
            type: 'warning',
            text: `<b>Tradelink required</b><br>Please <a href='/settings' title='Settings'>submit your Tradelink</a> in order to use all functions on this website.`,
            modal: true,
            force: true
        }).show();
    }
})();

$(document).ready(function setLanguage() {
    $('.language').on('click', function setLanguage() {
        let language = $(this).data('language');
        $('#language').val(language);
        Help.setCookie('language', language);
    });
});
