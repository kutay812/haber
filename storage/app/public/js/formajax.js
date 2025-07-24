let jquery_load = 0;

class Formajax {
    div;              // form element selector
    title;            // popup title
    pop;              // popup show true|false
    refresh;          // after send form refresh page true|false
    not_back;         // warning confirm true|false
    refresh_time;     // refresh time
    not_back_msg = "Bu işlem geriye alınamaz, onaylıyor musunuz?"; // warning message
    error = "Hata oluştu!"; // error message
    form = null;

    constructor(div = '', title = '', pop = false, refresh = false, not_back = false, refresh_time = 1000) {
        this.div = div;
        this.title = title;
        this.pop = pop;
        this.refresh = refresh;
        this.not_back = not_back;
        this.refresh_time = refresh_time;
        this.close(); // modal kapama listener
    }

    init() {
        var t = this;
        t.log(t.div + ' formajax');
        $(document).on('submit', t.div, function (e) {
            e.preventDefault();
            t.form = this;
            if (t.not_back) {
                $.confirm({
                    title: 'Uyarı!',
                    content: t.not_back_msg,
                    buttons: {
                        Evet: {
                            btnClass: 'btn-blue jquery_confirm_btn_blue',
                            action: function () {
                                t.step(t, t.form);
                            }
                        },
                        Hayır: {
                            btnClass: 'btn-red',
                            action: function () {
                                $.alert('İşlem iptal edildi!');
                            }
                        }
                    },
                    onContentReady: function () {
                        let self = this;
                        self.buttons.Evet.disable();
                        let i = 3;
                        let interval = setInterval(function () {
                            self.buttons.Evet.setText('Evet (' + i + ')');
                            if (i === 0) {
                                clearInterval(interval);
                                self.buttons.Evet.enable();
                                self.buttons.Evet.setText('Evet');
                            }
                            i--;
                        }, 1000);
                    }
                });
            } else {
                t.step(t, t.form);
            }
        });
    }

    step(t, thisform) {
        var form = $(thisform);
        var buttons = form.find('.btn');
        var lastBtn = $(buttons[buttons.length - 1]);
        lastBtn.attr('disabled', 'disabled');
        lastBtn.append('<div class="spinner-grow" role="status"><span class="visually-hidden">Loading...</span></div>');

        var formData2 = new FormData(thisform);
        var all = $(thisform).find("input[type=checkbox]");
        for (var i = 0; i < all.length; i++) {
            let name = $(all[i]).attr('name');
            if (typeof name !== 'undefined' && name !== false) {
                formData2.append(name, $(all[i]).prop('checked'));
            }
        }

        if (form.attr('method').toLowerCase() == 'get') {
            var get_url = "";
            all = $(thisform).find("input, select, textarea");
            for (var i = 0; i < all.length; i++) {
                let name = $(all[i]).attr('name');
                if (typeof name !== 'undefined' && name !== false) {
                    get_url += name + '=' + $(all[i]).val() + '&';
                }
            }
            all = $(thisform).find("input[type=checkbox]");
            for (var i = 0; i < all.length; i++) {
                let name = $(all[i]).attr('name');
                if (typeof name !== 'undefined' && name !== false) {
                    get_url += '&' + name + '=' + $(all[i]).prop('checked');
                }
            }
            formData2 = get_url.slice(0, -1);
        }

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: formData2,
            processData: form.attr('method').toLowerCase() === 'get',
            contentType: form.attr('method').toLowerCase() === 'get' ? 'application/x-www-form-urlencoded; charset=UTF-8' : false,
            success: function (data) {
                try {
                    if (typeof data === "string") data = JSON.parse(data);
                    if (t.pop) {
                        t.popup(data[1], t.title, t.div.replace('.', '').replace('#', '') + '_popup');
                    }
                    if (t.refresh && data[0]) {
                        setTimeout(() => location.reload(), t.refresh_time);
                    }
                } catch (e) {
                    if (t.pop) {
                        t.popup(data, t.title, t.div.replace('.', '').replace('#', '') + '_popup');
                    }
                    if (t.refresh) {
                        setTimeout(() => location.reload(), t.refresh_time);
                    }
                }
                lastBtn.removeAttr('disabled');
                lastBtn.find('.spinner-grow').remove();
            }
        }).fail(function (xhr, status, error) {
            t.popup(error, t.error);
            lastBtn.removeAttr('disabled');
            lastBtn.find('.spinner-grow').remove();
        });
    }

    popup(html, title, pop_class = '', max_height = "500px", max_width = "500px") {
        max_width = "max-width:" + max_width + ";";
        max_height = "max-height:" + max_height + ";";
        $('body').css('overflow-y', 'hidden');
        $('body').append(
            `<div class="modal pt-5 ${pop_class}" style="backdrop-filter: blur(4px);background: rgba(0,0,0,30%);z-index:10;display:block;" tabindex="-1" role="dialog">
                <div class="modal-dialog" style="${max_width}${max_height}" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="close btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ${html}
                        </div>
                    </div>
                </div>
            </div>`
        );
        $('.modal').show();
        $('html').css('overflow-y', 'hidden');
    }

    close() {
        $(document).on('click', '.modal .close', function () {
            $(this).closest('.modal').remove();
            $('html').css('overflow-y', 'auto');
        });
    }

    log(str) {
        console.log('%c LOG: ' + str, 'background: #a30000; color: white');
    }
}

function formajax(div, title, pop, refresh, not_back, refresh_time) {
    var fa = new Formajax(div, title, pop, refresh, not_back, refresh_time);
    try {
        $();
    } catch (e) {
        if (jquery_load == 0) {
            var s = document.createElement('script');
            s.setAttribute('src', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js');
            s.setAttribute('integrity', 'sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==');
            s.setAttribute('crossorigin', 'anonymous');
            s.setAttribute('referrerpolicy', 'no-referrer');
            document.body.appendChild(s);
            jquery_load++;
        }
    }
    var int = setInterval(function () {
        try {
            $();
            clearInterval(int);
            fa.init();
        } catch (e) { }
    }, 50);
}

// KULLANIM:
formajax('.formajax', 'Form Ajax', true, true, true, 1000);
new Formajax('.formajax2', 'Form Ajax 2', true, true, true, 1000);
// formajax('.formajax3', 'Form Ajax 3', true, true, true, 1000); // örnek kullanım
