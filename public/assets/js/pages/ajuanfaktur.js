(function () {
    const formAjuanfaktur = document.querySelector('#formAjuanfaktur');
    // Form validation for Add new record
    if (formAjuanfaktur) {
        const fv = FormValidation.formValidation(formAjuanfaktur, {
            fields: {

                tanggal: {
                    validators: {
                        notEmpty: {
                            message: 'Tanggal Pengajuan Harus Diisi !'
                        }
                    }
                },

                kode_pelanggan: {
                    validators: {
                        notEmpty: {
                            message: 'Pelanggan  Harus Diisi !'
                        }
                    }
                },

                jumlah_faktur: {
                    validators: {
                        notEmpty: {
                            message: 'Jumlah Faktur  Harus Diisi !'
                        }
                    }
                },


            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });
    }
})();
