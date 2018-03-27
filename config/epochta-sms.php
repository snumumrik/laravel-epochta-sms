<?php

return [

    /*
     * Ключи сервиса EPochta
     * https://atomic.center/settings/
     */
    'test_mode' => env('EPOCHTA_TEST_MODE', false),

    'private_key' => env('EPOCHTA_PRIVATE_KEY', ''),

    'public_key' => env('EPOCHTA_PUBLIC_KEY', ''),

    /*
     * Время жизни смс на сервисе (0 = max, 1, 6, 12, 24 часов)
     */
    'sms_lifetime' => env('EPOCHTA_SMS_LIFETIME', 0),

    /*
     * Валюта 'USD','GBP','UAH','RUB','EUR'
     */
    'currency' => env('EPOCHTA_CURRENCY', 'USD'),

    /*
     * Имя отправителя (max 11 символов)
     * Только если зарегистрировано на сервиси
     * EPochta, если нет - можно не изменять
     */
    'sender' => env('EPOCHTA_SENDER', 'Sender'),

    /**
     *
     * Для ниже приведенных, здесь, настроек, нужно таблица в БД - сделать миграцию.
     *
     */

    /*
     * Сохранять смс и их статусы в БД
     */
    'use_db' => false,

    /*
     * Время, после которого считать смс устаревшей, мин.
     * используется, например, при обновлении статуса в БД.
     */
    'is_old_after' => 360,

    /**
     * Настройки для повторных отправок (использует метод smsDbResendUndelivered())
     * Например, повторно отправлять смс созданные не ранее 4 мин, не позднее 7
     * которые еще не имеют меньше равно 2 повторных попыток отправок
     */
    'attempts_transfer' => [
        'min_minutes' => 4,
        'max_minutes' => 7,
        'max_attempt' => 2,
    ],

    /*
     * Сообщения для статусов для метода getGeneralStatus()
     */
    'human_statuses' => [
        0 => 'Ошибка! Сервис Epochta не возвращает ID',
        1 => 'Доставлено получателю',
        2 => 'Не доставлено. Получатель не доступен', // и не будет доставелно.
        3 => 'Отправлено получателю',
        4 => 'Отправлено на сервис Epochta',
    ],

    'route' => [
        'rule' => 'route',
        'type' => env('EPOCHTA_TYPE', 'direct'),
    ]
];
