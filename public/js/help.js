'use strict';
const Help = {
    currentTime(timezone) {
        return (new Date()).toLocaleTimeString([], {
            timeZone: (timezone ? timezone : 'GMT'),
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    },

    getCookie(key) {
        const value = "; " + document.cookie;
        const parts = value.split("; " + key + "=");
        return parts.length == 2 ? parts.pop().split(";").shift() : false;
    },

    setCookie(key, value) {
        const exp = new Date();
        exp.setTime(exp.getTime() + (365 * 24 * 60 * 60 * 1000));
        document.cookie = key + "=" + value + "; expires=" + exp.toUTCString();
    },

    decode(input) {
        const e = document.createElement('div');
        e.innerHTML = input;
        return e.childNodes[0].nodeValue;
    }
};