(function () {
    const formProsesajuantransferdana = document.querySelector('#formProsesajuantransferdana');
    // Form validation for Add new record
    if (formProsesajuantransferdana) {
        const fv = FormValidation.formValidation(formProsesajuantransferdana, {
            fields: {

                bukti: {
                    validators: {
                        notEmpty: {
                            message: 'Link Bukti Harus Diisi !'
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
