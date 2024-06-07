(function () {
    const formApprovetransfer = document.querySelector('#formApprovetransfer');
    // Form validation for Add new record
    if (formApprovetransfer) {
        const fv = FormValidation.formValidation(formApprovetransfer, {
            fields: {

                status: {
                    validators: {
                        notEmpty: {
                            message: 'Status Harus Diisi !'
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
