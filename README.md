# Laravel Epochta SMS

Package for sending SMS in Laravel. Using service https://www.epochta.ru

## Installation
1. Add 
```bash
composer require "fomvasss/laravel-epochta-sms"
```
---
### For Laravel < v5.5 !

Add the ServiceProvider to the providers array in config/app.php:

```php
Fomvasss\EpochtaService\SmsServiceProvider::class,
```

If you like use facades - add next aliase to aliases array

```php
'Sms' => Fomvasss\EpochtaService\Facade::class,
```

---

Publish the config file:

```bash
php artisan  vendor:publish --provider="Fomvasss\Epochta\SmsServiceProvider" --tag=config
```

Publish the migration file:

```bash
php artisan  vendor:publish --provider="Fomvasss\Epochta\SmsServiceProvider" --tag=migrations
``` 

or one command
```
php artisan  vendor:publish --provider="Fomvasss\Epochta\SmsServiceProvider"
```

and run migration:

```bash
php artisan migrate
```

## Usage

```php
    use Fomvasss\EpochtaService\Facade as Sms;

    protected $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function run()
    {
        $r = $this->sms->account()->getUserBalance();
        $r = $this->sms->stat()->sendSms('SenderTest', 'test sms text', '380656565656', '2017-10-31 16:08:00', '6');
        $r = $this->sms->stat()->getCampaignInfo(96972041);
        
        /*
         * sms text
         * phone number (+ key country)
         * array $attributes [lifetime | sender | info | datetime]
         */
        $r = $this->sms->statQueue()->addSmsQueue('Test SMS serv', '380969416874', ['info' => 'The registration new user'])
        $r = $this->sms->statQueue()->updateCampaignInfo();
    }
```

You can using the Facade (when added):

```php
    \Sms::account()->getUserBalance();
    \Sms::stat()->sendSms('SenderTest', 'test sms text', '380656565656', '2017-10-31 16:08:00', '6');
    \Sms::statQueue()->sendSms('SenderTest2', 'test sms text', '380656565656', '2017-10-31 16:08:00', '6');
    \Sms::statQueue()->updateCampaignInfo();
```

For example, add sms-queue to CRON (in Laravel app/Console/Kernel.php method `schedule`):

```php
    $schedule->call(function () {
        if (env('CRON_SMS')) {
            \Sms::statQueue()->sendSmsQueue();
            \Sms::statQueue()->updateCampaignInfoQueue();
        }
    })->cron('* * * * * *');
```

### More method and API see in official documentation:

- https://www.epochta.com.ua/products/sms/v3.php 
- https://www.epochta.ru/products/sms/php-30-example.php
