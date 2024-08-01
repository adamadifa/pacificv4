
<?php
return [
    'jenis_barang' => [
        'BB' =>  'BAHAN BAKU',
        'BT' => 'BAHAN TAMBAHAN',
        'KM' => 'KEMASAN',
        'LN' => 'LAINNYA'
    ],

    'group' => [
        'ADT' => 'AUDIT',
        'GAF' => 'GENERAL AFFAIR',
        'GDB' => 'GUDANG BAHAN',
        'GDL' => 'GUDANG LOGISTIK',
        'MKT' => 'MARKETING',
        'LNY' => 'LAINNYA',
        'TSM' => 'CABANG TASIKMALAYA',
        'HRD' => 'HRD'
    ],


    'list_jenis_barang' => [
        [
            'kode_jenis_barang' => 'BB',
            'nama_jenis_barang' => 'BAHAN BAKU'
        ],

        [
            'kode_jenis_barang' => 'BT',
            'nama_jenis_barang' => 'BAHAN TAMBAHAN'
        ],
        [
            'kode_jenis_barang' => 'KM',
            'nama_jenis_barang' => 'KEMASAN'
        ],
        [
            'kode_jenis_barang' => 'LN',
            'nama_jenis_barang' => 'LAINNYA'
        ],
    ],

    'list_group' => [
        [
            'kode_group' => 'ADT',
            'nama_group' => 'AUDIT'
        ],

        [
            'kode_group' => 'GAF',
            'nama_group' => 'GENERAL AFFAIR'
        ],
        [
            'kode_group' => 'GDB',
            'nama_group' => 'GUDANG BAHAN'
        ],
        [
            'kode_group' => 'GDL',
            'nama_group' => 'GUDANG LOGISTIK'
        ],
        [
            'kode_group' => 'MKT',
            'nama_group' => 'MARKETING'
        ],
        [
            'kode_group' => 'TSM',
            'nama_group' => 'CABANG TASIKMALAYA'
        ],
        [
            'kode_group' => 'HRD',
            'nama_group' => 'HRD'
        ],
        [
            'kode_group' => 'LNY',
            'nama_group' => 'LAINNYA'
        ],
    ],

    'asal_pengajuan' => [
        'GDL' => 'GUDANG LOGISTIK',
        'MKT' => 'MARKETING',
        'LNY' => 'LAINNYA',
        'GAF' => 'GENERAL AFFAIR',
        'GDB' => 'GUDANG BAHAN',
        'HRD' => 'HRD',
        'AID' => 'AIDA',
        'TSM' => 'CABANG TASIKMALAYA'
    ],

    'list_asal_pengajuan' => [


        [
            'kode_group' => 'GAF',
            'nama_group' => 'GENERAL AFFAIR'
        ],
        [
            'kode_group' => 'GDB',
            'nama_group' => 'GUDANG BAHAN'
        ],
        [
            'kode_group' => 'GDL',
            'nama_group' => 'GUDANG LOGISTIK'
        ],
        [
            'kode_group' => 'MKT',
            'nama_group' => 'MARKETING'
        ],
        [
            'kode_group' => 'HRD',
            'nama_group' => 'HRD'
        ],
        [
            'kode_group' => 'LNY',
            'nama_group' => 'LAINNYA'
        ],
    ],


    'role_access_all_pembelian' => [
        'super admin', 'direktur', 'admin pembelian', 'manager pembelian', 'manager accounting'
    ],
];
