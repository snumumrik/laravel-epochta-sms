# Laravel Epochta SMS

Package for sending SMS in Laravel. Using service https://www.epochta.ru

## Installation
1. Add 
```
composer require "fomvasss/laravel-epochta-sms"
```
---
### For Laravel < v5.5 !

Add the ServiceProvider to the providers array in config/app.php:

```Fomvasss\EpochtaService\SmsServiceProvider::class,```

If you like use facades - add next aliase to aliases array

```'Sms' => Fomvasss\EpochtaService\Facade::class,```

---

Publish the config file:

```php artisan  vendor:publish --provider="Fomvasss\Epochta\SmsServiceProvider" --tag=config```

Publish the migration file:

```php artisan  vendor:publish --provider="Fomvasss\Epochta\SmsServiceProvider" --tag=migrations``` 

or one command
```
php artisan  vendor:publish --provider="Fomvasss\Epochta\SmsServiceProvider"
```
and run migration:

```php artisan migrate```

## Usage

```php
use Fomvasss\EpochtaService\Sms;

protected $sms;

public function __construct(Sms $sms)
{
    $this->sms = $sms;
}

public function run()
{
    $r = $this->sms->account()->getUserBalance();
    $r = $this->sms->stat()->sendSms('SenderTest', 'test sms text', '+380656565656', '2017-10-31 16:08:00', '6');
    $r = $this->sms->stat()->getCampaignInfo(96972041);
    
    $r = $this->sms->statQueue()->addSmsQueue('Test SMS serv', '380969416874', ['info' => 'The registration new user'])
    $r = $this->sms->statQueue()->updateCampaignInfo();
}
```

You can using the Facade (when added):

```php
\Sms::account()->getUserBalance();
\Sms::statQueue()->updateCampaignInfo();
```


### More method and API see in official documentation:

- https://www.epochta.com.ua/products/sms/v3.php 
- https://www.epochta.ru/products/sms/php-30-example.php
