class FormAjax {
    constructor(formSelector) {
        this.debug = true;
        this.dependencies = ['jquery', 'jquery-confirm'];
        this.formSelector = formSelector;
        this.settings = new FormSettings();
        this.popupSize = 'col-md-12';

        if (this.loadDependencies()) {
            this.init();
        }
    }

    loadDependencies() {
        const missingDependencies = this.dependencies.filter(dep => {
            if (dep === 'jquery' && typeof jQuery === 'undefined') return true;
            if (dep === 'jquery-confirm' && typeof $.confirm === 'undefined') return true;
            return false;
        });

        if (missingDependencies.length > 0) {
            missingDependencies.forEach(dep => this.log(`${dep} not found`, 'error'));
            return false;
        }
        return true;
    }

    init() {
        $(document).on('submit', this.formSelector, (e) => {
            e.preventDefault();
            this.log(`Form submit: ${this.formSelector}`);
            this.handleSubmit(e.target);
        });
        this.log(`form_selector: ${this.formSelector}`);
    }

    handleSubmit(form) {
        this.popupSize = $(form).data('modal-size') || this.popupSize;
        this.log(`popup_size: ${this.popupSize}`);
        if (this.settings.confirm) {
            $.confirm({
                title: this.settings.confirmTitle,
                content: this.settings.confirmMsg,
                buttons: {
                    Evet: {
                        btnClass: 'btn-blue jquery_confirm_btn_blue',
                        action: () => this.submit(form),
                    },
                    Hayir: {
                        btnClass: 'btn-red',
                        action: () => $.alert('İşlem iptal edildi!'),
                    },
                },
            });
        } else {
            this.submit(form);
        }
    }

    setFormSettings(settings) {
        this.settings = settings;
    }

    submit(form) {
        const formData = new FormData(form);
        const formUrl = $(form).attr('action');
        const formMethod = ($(form).attr('method') || 'post').toLowerCase();

        this.log(`Form submit method: ${formMethod} | url: ${formUrl}`, 'info');

        $.ajax({
            url: formUrl,
            method: formMethod,
            data: formData,
            processData: false,
            contentType: false,
            success: (response, status, xhr) => {
                if (xhr.status === 200) {
                    if (response.status) {
                        this.handleSuccess(response);
                    } else {
                        this.handleError(response, 200);
                    }
                } else {
                    this.handleError(xhr);
                }
            },
            error: (xhr) => this.handleError(xhr),
        });
    }

    handleSuccess(response) {
        if (this.settings.openPopup) {
            $.alert({
                columnClass: this.popupSize + ' col-md-offset-3',
                animationSpeed: 0,
                backgroundDismiss: true,
                title: this.settings.title,
                content: response.message,
                closeIcon: true,
                type: 'orange',
                buttons: {
                    Tamam: {
                        isHidden: true,
                        btnClass: 'btn-blue jquery_confirm_btn_blue',
                        action: () => {
                            if (this.settings.refresh) {
                                setTimeout(() => location.reload(), this.settings.refreshTime);
                            }
                        },
                    },
                },
            });
        }
        if (this.settings.refresh) {
            setTimeout(() => location.reload(), this.settings.refreshTime);
        }
    }

    handleError(xhrOrMsg, type = 'error') {
        let errorMessage = '';
        if (type === 200) {
            errorMessage = xhrOrMsg.message || 'İşlem başarısız!';
        } else if (xhrOrMsg && xhrOrMsg.responseJSON) {
            errorMessage = xhrOrMsg.responseJSON.message || 'Hata oluştu!';
        } else {
            errorMessage = xhrOrMsg.statusText || 'Bilinmeyen hata oluştu!';
        }

        if (this.settings.openPopup) {
            $.alert({
                columnClass: this.popupSize + ' col-md-offset-3',
                title: 'Hata',
                content: errorMessage,
                buttons: {
                    Tamam: {
                        btnClass: 'btn-red',
                    },
                },
            });
        }
    }

    log(message, type = 'info') {
        if (!this.debug) return;
        const logTypes = {
            info: { background: '#007bff', color: 'white' },
            error: { background: '#dc3545', color: 'white' },
            warning: { background: '#ffc107', color: 'black' },
            success: { background: '#28a745', color: 'white' },
        };
        const { background, color } = logTypes[type] || logTypes.info;
        console.log(`%c${message}`, `background: ${background}; color: ${color}; padding: 5px; border-radius: 5px;`);
    }
}

class FormSettings {
    constructor({
        title = 'İşlem Sonucu',
        openPopup = false,
        confirm = false,
        confirmTitle = 'Uyarı!',
        confirmMsg = 'Bu işlemi gerçekleştirmek istediğinize emin misiniz?',
        refresh = false,
        refreshTime = 1000,
    } = {}) {
        this.title = title;
        this.openPopup = openPopup;
        this.confirm = confirm;
        this.confirmTitle = confirmTitle;
        this.confirmMsg = confirmMsg;
        this.refresh = refresh;
        this.refreshTime = refreshTime;
    }
}

// KULLANIM:
const formajax = new FormAjax('.formajax');
formajax.setFormSettings(new FormSettings({}));

const formajax_refresh = new FormAjax('.formajax_refresh');
formajax_refresh.setFormSettings(new FormSettings({ refresh: true }));

const formajax_confirm = new FormAjax('.formajax_confirm');
formajax_confirm.setFormSettings(new FormSettings({ confirm: true, confirmMsg: 'Bu işlemi gerçekleştirmek istediğinize emin misiniz?' }));

const formajax_delete = new FormAjax('.formajax_delete');
formajax_delete.setFormSettings(new FormSettings({ confirm: true, confirmMsg: 'Bu işlem geriye alınamaz, onaylıyor musunuz?', refresh: true, refreshTime: 2000, title: 'Silme Sonucu', openPopup: true }));

const formajax_edit = new FormAjax('.formajax_edit');
formajax_edit.setFormSettings(new FormSettings({ openPopup: true, title: 'Düzenleme Sonucu' }));

const formajax_view = new FormAjax('.formajax_view');
formajax_view.setFormSettings(new FormSettings({ openPopup: true, title: 'Görüntüleme Sonucu' }));

const formajax_popup = new FormAjax('.formajax_popup');
formajax_popup.setFormSettings(new FormSettings({ openPopup: true, title: 'İşlem Sonucu' }));

const formajax_refresh_popup = new FormAjax('.formajax_refresh_popup');
formajax_refresh_popup.setFormSettings(new FormSettings({ openPopup: true, title: 'İşlem Sonucu', refresh: true, refreshTime: 2000 }));

// Ekstra: Eğer sadece bir tane global ayar yapmak istersen
// const allForms = new FormAjax('form');
// allForms.setFormSettings(new FormSettings({ ... }));
