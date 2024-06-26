(function () {
    const formcreateBarangpembelian = document.querySelector('#formcreateBarangpembelian');
    const submitButton = document.querySelector('button[type="submit"]');
    // Form validation for Add new record
    if (formcreateBarangpembelian) {
        const fv = FormValidation.formValidation(formcreateBarangpembelian, {
            fields: {
                kode_barang: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Barang Harus Diisi'
                        },
                        stringLength: {
                            max: 7,
                            message: 'Maksimal 7 Karakter '
                        },
                    }
                },
                nama_barang: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Barang Harus Diisi'
                        }
                    }
                },

                satuan: {
                    validators: {
                        notEmpty: {
                            message: 'Satuan Harus Diisi'
                        }
                    }
                },

                kode_jenis_barang: {
                    validators: {
                        notEmpty: {
                            message: 'Jenis Barang Harus Diisi'
                        }
                    }
                },

                kode_kategori: {
                    validators: {
                        notEmpty: {
                            message: 'Kategori Harus Diisi'
                        }
                    }
                },
                kode_group: {
                    validators: {
                        notEmpty: {
                            message: 'Group Harus Diisi'
                        }
                    }
                },

                status: {
                    validators: {
                        notEmpty: {
                            message: 'Status Aktif Barang Harus Diisi'
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
        }).on('core.form.valid', function () {
            // Disable the submit button
            submitButton.setAttribute('disabled', true);

            // Do something else such as sending the form to back-end via Ajax request
            // ...
        });
    }
})();
