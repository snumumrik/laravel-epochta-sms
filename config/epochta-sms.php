<?php

return [
    
    /*
     * https://atomic.center/settings/
     */
    'private_key' => env('EPOCHTA_PRIVATE_KEY', ''),

    'public_key' => env('EPOCHTA_PUBLIC_KEY', ''),

    
    'test_mode' => env('EPOCHTA_TEST_MODE', true),

    /*
     * Life time (0 = max, 1, 6, 12, 24 hours)
     */
    'sms_lifetime' => env('EPOCHTA_SMS_LIFETIME', 0),

    /*
     * Default currency 'USD','GBP','UAH','RUB','EUR'
     */
    'currency' => env('EPOCHTA_CURRENCY', 'USD'),

    /*
     * Sender name (max 11 symbols)
     */
    'sender' => env('EPOCHTA_SENDER', 'Sender'),

    /*
     * Life time sms queue active (hours)
     */
    'lifetime_queue' => 24,

    /*
     * SMS DB table name for queue
     */
    'sms_db_table_name' => 'epochta_sms',
];
