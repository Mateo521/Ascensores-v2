<?php
return [
 
    'public_path' => env('DOMPDF_PUBLIC_PATH', public_path()),

 
    'chroot' => env('DOMPDF_CHROOT', base_path()),

    'options' => [
        'isRemoteEnabled' => (bool) env('DOMPDF_ENABLE_REMOTE', true),
        'isHtml5ParserEnabled' => true,
    ],
];
