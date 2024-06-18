(function () {
    const formAjuantransferdana = document.querySelector('#formAjuantransferdana');
    // Form validation for Add new record
    if (formAjuantransferdana) {
        const fv = FormValidation.formValidation(formAjuantransferdana, {
            fields: {

                tanggal: {
                    validators: {
                        notEmpty: {
                            message: 'Tanggal Harus Diisi !'
                        }
                    }
                },

                nama: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Penerima Harus Diisi !'
                        }
                    }
                },

                nama_bank: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Bank Harus Diisi !'
                        }
                    }
                },

                jumlah: {
                    validators: {
                        notEmpty: {
                            message: 'Jumlah Harus Diisi !'
                        }
                    }
                },

                keterangan: {
                    validators: {
                        notEmpty: {
                            message: 'Keterangan Harus Diisi !'
                        }
                    }
                },

                kode_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Cabang Harus Diisi !'
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
