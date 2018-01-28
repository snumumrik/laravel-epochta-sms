<?php

namespace Fomvasss\EpochtaService\Commands;

use Fomvasss\EpochtaService\Facade\Sms;
use Illuminate\Console\Command;

class SmsUpdateStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:update-statuses
            {--is_old=30 : Время, после которого считать смс устаревшей, мин.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновить статусы отправленных смс';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Sms::stat()->smsDbUpdateStatuses($this->option('is_old'));
    }
}
