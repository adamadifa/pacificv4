(function () {
    const formAjuanlimit = document.querySelector('#formAjuanlimit');
    // Form validation for Add new record
    if (formAjuanlimit) {
        const fv = FormValidation.formValidation(formAjuanlimit, {
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

                nama_pelanggan: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Pelanggan  Harus Diisi !'
                        }
                    }
                },

                nik: {
                    validators: {
                        notEmpty: {
                            message: 'NIK / No. KTP  Harus Diisi !'
                        }
                    }
                },

                alamat_pelanggan: {
                    validators: {
                        notEmpty: {
                            message: 'Alamat Pelanggan  Harus Diisi !'
                        }
                    }
                },

                no_hp_pelanggan: {
                    validators: {
                        notEmpty: {
                            message: 'No. HP  Harus Diisi !'
                        }
                    }
                },



                hari: {
                    validators: {
                        notEmpty: {
                            message: 'Hari  Harus Diisi !'
                        }
                    }
                },



                lokasi: {
                    validators: {
                        notEmpty: {
                            message: 'Lokasi  Harus Diisi !'
                        }
                    }
                },

                jumlah: {
                    validators: {
                        notEmpty: {
                            message: 'Jumlah Ajuan Limit  Harus Diisi !'
                        }
                    }
                },

                ljt: {
                    validators: {
                        notEmpty: {
                            message: 'LJT  Harus Diisi !'
                        }
                    }
                },

                kepemilikan: {
                    validators: {
                        notEmpty: {
                            message: 'Kepemilikan  Harus Diisi !'
                        }
                    }
                },

                lama_berjualan: {
                    validators: {
                        notEmpty: {
                            message: 'Lama Usaha  Harus Diisi !'
                        }
                    }
                },

                status_outlet: {
                    validators: {
                        notEmpty: {
                            message: 'Status Outlet  Harus Diisi !'
                        }
                    }
                },

                type_outlet: {
                    validators: {
                        notEmpty: {
                            message: 'Type Outlet  Harus Diisi !'
                        }
                    }
                },

                cara_pembayaran: {
                    validators: {
                        notEmpty: {
                            message: 'Cara Pembayaran  Harus Diisi !'
                        }
                    }
                },

                lama_langganan: {
                    validators: {
                        notEmpty: {
                            message: 'Lama Langganan  Harus Diisi !'
                        }
                    }
                },

                jaminan: {
                    validators: {
                        notEmpty: {
                            message: 'Jaminan  Harus Diisi !'
                        }
                    }
                },

                histori_transaksi: {
                    validators: {
                        notEmpty: {
                            message: 'Histori Pembayaran 6 Bulan Terakhir   Harus Diisi !'
                        }
                    }
                },

                topup_terakhir: {
                    validators: {
                        notEmpty: {
                            message: 'Top UP Teraekhir   Harus Diisi !'
                        }
                    }
                },

                omset_toko: {
                    validators: {
                        notEmpty: {
                            message: 'Omset Toko Harus Diisi !'
                        }
                    }
                },

                uraian_analisa: {
                    validators: {
                        notEmpty: {
                            message: 'Analisa Harus Diisi !'
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
                instance.on('core.form.valid', function () {
                    // Disable the submit button
                    formAjuanlimit.querySelector('input[type="submit"]').disabled = true;
                    formAjuanlimit.submit();
                });
                instance.on('core.form.invalid', function () {
                    // Enable the submit button
                    formAjuanlimit.querySelector('input[type="submit"]').disabled = false;
                });
            }
        });
    }
})();
