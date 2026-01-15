<?php


return [
    'GBR' => [
        'GBP' => [
            [
                'key'      => 'account_type',
                'label'    => 'Account Type',
                'type'     => 'selected',
                'required' => true,
                'options'  => [
                    'swift_code' => 'SWIFT',
                    'ach'        => 'ACH',
                ],
            ],
            [
                'key'      => 'swiftCode',
                'label'    => 'SWIFT Code',
                'type'     => 'text',
                'required' => true,
            ],
            [
                'key'   => 'legal_type',
                'label' => 'Legal Type',
                'type'  => 'selected',
                'options' => [
                    "PRIVATE" => 'Private',
                    'BUSINESS' => 'Business',
                ],
            ],
        ],
    ],


    'USA' => [
        'USD' => [
            [
                'key'      => 'account_type',
                'label'    => 'Account Type',
                'type'     => 'selected',
                'required' => true,
                'options'  => [
                    'swift' => 'SWIFT',
                    'ach'        => 'ACH',
                ],
            ],
            [
                'key'      => 'swift_code',
                'label'    => 'SWIFT Code',
                'type'     => 'text',
                'required' => true,
            ],
            [
                'key'   => 'legal_type',
                'label' => 'Legal Type',
                'type'  => 'selected',
                'options' => [
                    "PRIVATE" => 'Private',
                    'BUSINESS' => 'Business',
                ],
            ],
        ],
    ],
    // 

];
