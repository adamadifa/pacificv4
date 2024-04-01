(function () {
    const frmLaporanmutasiproduksi = document.querySelector('#frmLaporanmutasiproduksi');
    // Form validation for Add new record
    if (frmLaporanmutasiproduksi) {
        const fv = FormValidation.formValidation(frmLaporanmutasiproduksi, {
            fields: {
                kode_produk: {
                    validators: {
                        notEmpty: {
                            message: 'Produk Harus Dipilih '
                        },
                    }
                },
                dari: {
                    validators: {
                        notEmpty: {
                            message: 'Periode Dari Tidak Boleh Kosong '
                        },
                    }
                },
                sampai: {
                    validators: {
                        notEmpty: {
                            message: 'Periode Sampai Tidak Boleh Kosong '
                        },
                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton({
                    //-- make multiple submits work
                    aspNetButton: true
                }),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
                startEndDate: new FormValidation.plugins.StartEndDate({
                    format: 'YYYY-MM-DD',
                    startDate: {
                        field: 'dari',
                        message: 'Tanggal Tidak Valid',
                    },
                    endDate: {
                        field: 'sampai',
                        message: 'Tanggal Tidak Valid',
                    },
                }),
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
