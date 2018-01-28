<?php

namespace Fomvasss\EpochtaService\Commands;

use Fomvasss\EpochtaService\Facade\Sms;
use Illuminate\Console\Command;

class SmsSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send
            {--phone= : Номер получателя}
            {--text= : Текст СМС}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправаить смс на указанный номер';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $phone = $this->option('phone');
        $text = $this->option('text');

        if (empty($phone) || empty($text)) {
            $phone = $this->ask('Введети номер телефона на который нужно отправить:');
            $text = $this->ask('Введети текст смс:');
            if ($this->confirm('Отправлять?')) {
                $r = Sms::stat()->sendSMS($text, $phone);
                empty($r['result']['id']) ? $this->warn('Не отправлено. Что-то пошло не так :(') : $this->info('Успешно отправлено ID: '.$r['result']['id']);
            }
        } else {
            Sms::stat()->sendSMS($text, $phone);
        }
    }
}
