<?php



return [
    'operation manager' => [
        'dept' => ['AKT'],
        'jabatan' => ['J13', 'J14', 'J15', 'J17', 'J18'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1
    ],

    'sales marketing manager' => [
        'dept' => ['MKT'],
        'jabatan' => ['J18', 'J19', 'J21', 'J12', 'J22', 'J23', 'J08', 'J13', 'J14', 'J15', 'J17', 'J18'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1
    ],

    'regional operation manager' => [
        'dept' => ['AKT'],
        'jabatan' => ['J12', 'J13'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => true,
        'cabang' => 0
    ],

    'regional sales manager' => [
        'dept' => ['MKT'],
        'jabatan' => ['J07'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => true,
        'cabang' => 2

    ],

    'gm administrasi' => [
        'dept' => ['AKT', 'KEU'],
        'jabatan' => [],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 0
    ],


    'gm marketing' => [
        'dept' => ['MKT'],
        'jabatan' => ['J05', 'J03'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => true,
        'cabang' => 0
    ],


    'gm operasional' => [
        'dept' => ['GDG', 'GAF', 'PRD', 'PMB', 'MTC', 'HRD'],
        'jabatan' => ['J05', 'J06'],
        'dept2' => ['PDQ'],
        'jabatan2' => [],
        'jabatan_filter' => true,
        'cabang' => 1,
    ],
    'manager keuangan' => [
        'dept' => ['AKT', 'KEU'],
        'jabatan' => [],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 0
    ],

    'manager pembelian' => [
        'dept' => ['PMB'],
        'jabatan' => ['J12'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1
    ],

    'manager gudang' => [
        'dept' => ['GDG'],
        'jabatan' => ['J13', 'J16', 'J12', 'J15', 'J18'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1
    ],

    'manager general affair' => [
        'dept' => ['GAF'],
        'jabatan' => ['J15', 'J16', 'J17', 'J20', 'J12'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1,
    ],

    'manager audit' => [
        'dept' => ['ADT'],
        'jabatan' => ['J12'],
        'jabatan_filter' => false,
        'cabang' => 1
    ],

    'manager produksi' => [
        'dept' => ['PRD'],
        'jabatan' => ['J16', 'J09', 'J11', 'J06'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1,
    ],

    'manager maintenance' => [
        'dept' => ['MTC'],
        'jabatan' => ['J16', 'J11', 'J14'],
        'dept2' => [],
        'jabatan2' => [],
        'jabatan_filter' => false,
        'cabang' => 1
    ],


    'asst. manager hrd' => [
        'dept' => ['HRD'],
        'jabatan' => ['J12'],
        'dept2' => [],
        'jabatan2' => ['J02'],
        'jabatan_filter' => false,
        'cabang' => 0
    ],


    'level_hrd' => ['asst. manager hrd', 'spv presensi'],

];
