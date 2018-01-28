<?php

namespace Fomvasss\EpochtaService\Commands;

use Fomvasss\EpochtaService\Facade\Sms;
use Illuminate\Console\Command;

class SmsResendUndelivered extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:resend-undelivered
            {--min=4 : СМС котории созданы не ранее чем _ мин. назад}
            {--max=7 : СМС котории созданы не позднее чем _ мин. назад}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Повторно отправить смс, которые подходят по времени создания, и для которых не было повторных отправок';

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
        Sms::stat()->smsDbResendUndelivered($this->option('min'), $this->option('max'));
    }
}
