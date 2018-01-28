<?php

namespace Fomvasss\EpochtaService;

use Fomvasss\EpochtaService\Events\AfterSendingSmsEvent;
use Fomvasss\EpochtaService\Events\BeforeSendingSmsEvent;
use Fomvasss\EpochtaService\Models\EpochtaSms;

/**
 * Class State
 *
 * @package \Fomvasss\EpochtaService
 */
class Stat extends \Stat
{
    use CheckResult;

    /**
     * Создать и отправить смс.
     *
     * @param $sender
     * @param $body
     * @param $phone
     * @param null $datetime
     * @param int $smsLifetime
     * @return bool|mixed
     */
    public function sendSMS($body, $phone, $sender = null, $datetime = null, $smsLifetime = null)
    {
        $sender = $this->checkSender($sender);
        $phone = $this->clearPhone($phone);
        $smsLifetime = $this->getLifeTime($smsLifetime);

        $attributes = [
            'sender' => $sender,
            'phone' => $phone,
            'body' => $body,
            'datetime' => $datetime,
            'lifetime' => $smsLifetime,
        ];

        event(new BeforeSendingSmsEvent($attributes));

        $smsModel = null;
        if ($useDb = config('epochta-sms.use_db', false)) {
            $smsModel = $this->smsDbSaveNew($attributes);
        }

        $result = parent::sendSMS($sender, $body, $phone, $datetime, $smsLifetime);

        event(new AfterSendingSmsEvent($attributes, $result, $smsModel));

        if (!empty($smsModel)) {
            $this->smsDbUpdateAfterSend($smsModel, $result);
        }

        return $this->checkResult($result) ?? [];
    }

    /**
     * Получить информацию об отправленной смс/компании.
     *
     * @param $id
     * @return bool|mixed
     */
    public function getCampaignInfo($id)
    {
        $res = parent::getCampaignInfo($id);

        if ($this->checkResult($res)) {
            // Событие после успешного получения статуса смс
            if (config('epochta-sms.use_db', false) && ($sms = EpochtaSms::where('sms_id', $id)->first())) {
                $this->smsDbUpdate($sms, $res);
            }

            return $res;
        }

        return [];
    }

    /**
     * Обновить статус смс в БД.
     *
     * @param $sms
     * @param $campaignInfoResult
     * @return mixed
     */
    public function smsDbUpdate(EpochtaSms $sms, $campaignInfoResult)
    {
        if ($campaignInfoResult && isset($campaignInfoResult['result']['sent'])) {
            $sms->sms_sent_status = $campaignInfoResult['result']['sent']; // состояние epochta отправки смс, 1 - отправлено получателю
            if ($campaignInfoResult['result']['delivered'] == 1) {
                $sms->sms_delivered_status = 1; // доставлено смс получателю
            }
            if ($campaignInfoResult['result']['not_delivered'] == 1) {
                $sms->sms_delivered_status = 2; // недоставлено смс получателю (и уже не будет!)
            }
            $sms->dispatch_status = $campaignInfoResult['result']['status'];  // состояние рассылки
            $sms->save();
        }

        return $sms;
    }

    /**
     * Получить (и обновить) статусы всех не обновленных (и не старых) смс.
     *
     * @param int|null $isOlderAfter
     * @return bool
     */
    public function smsDbUpdateStatuses(int $isOlderAfter = null)
    {
        $isOldAfter = $isOlderAfter ?: config('epochta-sms.is_old_after', 360);

        EpochtaSms::whereNotNull('sms_id')
            ->whereNull('sms_delivered_status')
            ->where('created_at', '>', \Carbon\Carbon::now()->addHour(-$isOldAfter))
            ->where('updated_at', '<', \Carbon\Carbon::now()->addMinute(-1))
            ->chunk(100, function ($messages) {
                foreach ($messages as $sms) {
                    $this->getCampaignInfo($sms->sms_id);
                }
            });

        return true;
    }

    /**
     *
     * Повтороно отправить все смс со статусом "Не доставлено",
     * которые еще не отправлялись повторно
     * котории созданы не позднее чем $maxMinutes мин. назад
     * и котории созданы не ранее чем $minMinutes мин. назад
     *
     * @param int $minMinutes
     * @param int $maxMinutes
     * @return bool
     */
    public function smsDbResendUndelivered(int $minMinutes = null, int $maxMinutes = null)
    {
        $minMinutes = $minMinutes ?: config('epochta-sms.attempts_transfer.min_minutes', 4);
        $maxMinutes = $maxMinutes ?: config('epochta-sms.attempts_transfer.max_minutes', 7);

        EpochtaSms::whereNull('resend_sms_id')
            ->where('sms_delivered_status', '<>', 1)
            ->where('created_at', '>', \Carbon\Carbon::now()->addMinute(-$maxMinutes))
            ->where('created_at', '<', \Carbon\Carbon::now()->addMinute(-$minMinutes))
            ->chunk(100, function ($messages) {
                foreach ($messages as $sms) {
                    $this->smsDbResend($sms);
                }
            });

        return true;
    }

    /**
     * Повторно отправить смс - создать новую запись в БД.
     *
     * @param \Fomvasss\EpochtaService\Models\EpochtaSms $sms
     * @return array|mixed
     */
    public function smsDbResend(EpochtaSms $sms)
    {
        $result = $this->sendSMS($sms->body, $sms->phone, $sms->sender, $sms->datetime, $sms->smsLifetime);

        if (! empty($result['result']['id'])) {
            $sms->resend_sms_id = $result['result']['id'];
            $sms->save();
        }

        return $result;
    }

    /**
     * Получить "удобочитаемый" статус для вывода.
     * https://www.epochta.com.ua/products/sms/v3.php
     *
     * @param \Fomvasss\EpochtaService\Models\EpochtaSms $sms
     * @return string
     */
    public function getGeneralStatus(EpochtaSms $sms)
    {
        $stat = 0;
        if ($sms->sms_delivered_status == 1) {
            $stat = 1;
        } elseif ($sms->sms_delivered_status == 2) {
            $stat = 2;
        } elseif ($sms->sms_sent_status == 1) {
            $stat = 3;
        } elseif ($sms->sms_id) {
            $stat = 4;
        }

        return config('epochta-sms.human_statuses')[$stat] ?? 'Error';
    }


    /**
     * Сохранить новую смс.
     *
     * @param $attributes
     * @return mixed
     */
    protected function smsDbSaveNew($attributes)
    {
        return EpochtaSms::create($attributes);
    }

    /**
     * Обновить данные смс после отправки.
     *
     * @param $sendingResult
     * @param $smsModel
     * @return mixed
     */
    protected function smsDbUpdateAfterSend($smsModel, $sendingResult)
    {
        if (! empty($sendingResult['result']['id'])) {
            $smsModel->sms_id = $sendingResult['result']['id']; // ид смс на сервисе epochta
            $smsModel->sms_delivered_status = null;
            $smsModel->save();
        }

        return $smsModel;
    }


    /**
     * @param $str
     * @return mixed
     */
    protected function clearPhone($str)
    {
        return preg_replace('/[^0-9]/si', '', $str);
    }

    /**
     * @param string|null $senderName
     * @return bool|string
     */
    protected function checkSender(string $senderName = null)
    {
        return substr($senderName ?? config('epochta-sms.sender', 'Sender'), 0, 11);
    }

    /**
     * @param null $smsLifetime
     * @return \Illuminate\Config\Repository|mixed|null
     */
    protected function getLifeTime($smsLifetime = null)
    {
        return $smsLifetime ?? config('epochta-sms.sms_lifetime', 0);
    }
}
