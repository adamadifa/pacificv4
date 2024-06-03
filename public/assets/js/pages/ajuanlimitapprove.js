(function () {
    const formApprovelimit = document.querySelector('#formApprovelimit');
    // Form validation for Add new record
    if (formApprovelimit) {
        const fv = FormValidation.formValidation(formApprovelimit, {
            fields: {

                uraian_analisa: {
                    validators: {
                        notEmpty: {
                            message: 'Uraian Analisa Harus Diisi !'
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
