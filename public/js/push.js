Noty.overrideDefaults({
    layout: 'center',
});

const Push = {
    error(data) {
        let error = 'Undefined Error. Contact an Admin.';
        if (data) {
            if (data.error) {
                if (typeof data.error === 'string' || data.error instanceof String) {
                    error = data.error;
                }
            }
            else if (typeof data === 'string' || data instanceof String) {
                error = data;
            }
        }
        return new Noty({
            type: 'error',
            text: '<p>' + error + '</p>'
        }).show();
    },

    success(html) {
        if (typeof html === 'string' || html instanceof String) {
            new Noty({
                type: 'success',
                text: html
            }).show();
        }
        else {
            new Noty({
                type: 'error',
                text: (html.error === 'string' || html.error instanceof String) ? html.error : 'Undefined Error. Contact an Admin.'
            }).show();
        }
    },

    warning(html) {
        if (typeof html === 'string' || html instanceof String) {
            new Noty({
                type: 'warning',
                text: html
            }).show();
        }
        else {
            new Noty({
                type: 'error',
                text: (html.error === 'string' || html.error instanceof String) ? html.error : 'Undefined Error. Contact an Admin.'
            }).show();
        }
    },

    info(html) {
        if (typeof html === 'string' || html instanceof String) {
            new Noty({
                type: 'info',
                text: html
            }).show();
        }
        else {
            new Noty({
                type: 'error',
                text: (html.error === 'string' || html.error instanceof String) ? html.error : 'Undefined Error. Contact an Admin.'
            }).show();
        }
    },

    alert(html) {
        if (typeof html === 'string' || html instanceof String) {
            new Noty({
                type: 'alert',
                text: html
            }).show();
        }
        else {
            new Noty({
                type: 'error',
                text: (html.error === 'string' || html.error instanceof String) ? html.error : 'Undefined Error. Contact an Admin.'
            }).show();
        }
    }
};