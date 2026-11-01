<?php

return [
    'base_url'        => env('BRIDGE_BASE_URL', 'http://127.0.0.1:3000'),
    'api_key'         => env('BRIDGE_API_KEY', ''),
    'timeout'         => (int) env('BRIDGE_TIMEOUT', 4),
    'connect_timeout' => (int) env('BRIDGE_CONNECT_TIMEOUT', 1),
];