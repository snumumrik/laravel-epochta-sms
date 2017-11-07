<?php

namespace Fomvasss\EpochtaService;

use Fomvasss\EpochtaService\Models\EpochtaSms;

/**
 * Class State
 *
 * @package \Fomvasss\EpochtaService
 */
class StatQueue extends Stat
{

    use CheckResult;

    /**
     * Добавить СМС в очередь
     * 
     * @param $text
     * @param $phone
     * @param array $attributes [lifetime | sender | info | datetime]
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function addSmsQueue($text, $phone, $attributes = [])
    {
        $phone = epochta_clear_phone($phone);
        $lifetime = $attributes['lifeime'] ?? config('epochta-sms.sms_lifetime', 0);
        $sender = substr($attributes['sender'] ?? config('epochta-sms.sender', 'Sender'), 0, 11);
        $info = $attributes['info'] ?? null;
        $datetime = $attributes['datetime'] ?? null;

        $sms = EpochtaSms::create([
            'sender' => $sender,
            'phone' => $phone,
            'text' => $text,
            'info' => $info,
            'datetime' => $datetime,
            'lifetime' => $lifetime,
        ]);

        return $sms;
    }

    /**
     * Отправить очередь смс
     *
     * @return bool
     */
    public function sendSmsQueue()
    {
        $lifetimeQueue = config('epochta-sms.lifetime_queue', 24);
        
        $smss = EpochtaSms::where('transfer_status', 0)
            ->where('created_at', '>', \Carbon\Carbon::now()->addHour(-1*$lifetimeQueue))
            ->chunk(100, function ($smss) {
                foreach ($smss as $sms) {
                    $res = $this->sendSMS(
                        $sms->sender, $sms->text, $sms->phone, $sms->datetime ? $sms->datetime : null, $sms->lifetime
                    );
                    $sms->transfer_count = $sms->transfer_count + 1;
                    if ($res && isset($res['result']['id'])) {
                        $sms->transfer_status = 1;
                        $sms->sms_id = $res['result']['id'];
                    }
                    $sms->save();
                }
            });
        return true;
    }

    /**
     * Получить статусы смс с очереди
     * 
     * @return bool
     */
    public function updateCampaignInfoQueue()
    {
        $lifetimeQueue = config('epochta-sms.lifetime_queue', 24);
        
        $smss = EpochtaSms::where('transfer_status', 1)
            ->where('created_at', '>', \Carbon\Carbon::now()->addHour(-1*$lifetimeQueue))
            ->whereNull('sms_delivered_status')
            ->chunk(100, function ($smss) {
                foreach ($smss as $sms) {
                    $res = $this->getCampaignInfo($sms->sms_id);
                    if ($res && isset($res['result']['sent'])) {
                        $sms->sms_sent_status = 1;
                        if ($res['result']['delivered'] == 1) {
                            $sms->sms_delivered_status = 1; // Доставлено
                        }
                        if ($res['result']['not_delivered'] == 1) {
                            $sms->sms_delivered_status = 2; // Не доставлено
                        }
                        $sms->dispatch_status = $res['result']['status'];
                    }
                    $sms->save();
                }
            });
        return true;
    }
    
}
