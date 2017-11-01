<?php

namespace Fomvasss\EpochtaService;

class Sms
{

    protected $gateway;

    protected $config;

    public function __construct()
    {
        $this->config = config('epochta-sms');
        $privateKey = $this->config['private_key'];
        $publicKey = $this->config['public_key'];
        $url = 'https://atompark.com/api/sms/';
        $testMode = $this->config['test_mode'];
        $version = '3.0';
        $formatResponse = 'json';

        $this->gateway = new \APISMS($privateKey, $publicKey, $url, $testMode, $version, $formatResponse);
    }

    public function addressbook()
    {
        return new Addressbook($this->gateway);
    }

    public function exceptions()
    {
        return new Exceptions($this->gateway);
    }

    public function account()
    {
        return new Account($this->gateway);
    }

    public function stat()
    {
        return new Stat($this->gateway);
    }

    public function statQueue()
    {
        return new StatQueue($this->gateway);
    }
}
