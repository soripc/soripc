<?php

return [
    'signature_note' => env('SIGNATURE_NOTE_OSE', 'QPOS'),
    'signature_uri' => env('SIGNATURE_URI_OSE', 'signatureQPOS'),
    'api_service_url' => env('API_SERVICE_URL'),
    'api_service_token' => env('API_SERVICE_TOKEN', false),
    'sunat_alternate_server' => env('SUNAT_ALTERNATE_SERVER', false),
    'app_url_base' => env('APP_URL_BASE'),
    'is_demo' => env('IS_DEMO', false),

];
