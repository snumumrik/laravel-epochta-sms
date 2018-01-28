<?php

namespace Fomvasss\EpochtaService\Commands;

use Fomvasss\EpochtaService\Facade\Sms;
use Illuminate\Console\Command;

class SmsCheckStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:check
            {--id= : ID смс}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверить статус смс';

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
        $id = $this->option('id');

        if (empty($id)) {
            $id = $this->ask('Введети ID смс, полученный при отправке:');
        }

        $r = Sms::stat()->getCampaignInfo($id);
        if (isset($r['result']['delivered']) && $r['result']['delivered'] == 1) {
            $this->info('Доставлено получателю!');
        } elseif (isset($r['result']['not_delivered']) && $r['result']['not_delivered'] == 1) {
            $this->warn('Не доставлено получателю!');
        } else {
            $this->warn('Идет отправка...');
        }
    }
}
