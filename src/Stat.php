<?php

namespace Fomvasss\EpochtaService;

/**
 * Class State
 *
 * @package \Fomvasss\EpochtaService
 */
class Stat extends \Stat
{

    use CheckResult;

    /**
     * @param $sender
     * @param $text
     * @param $phone
     * @param null $datetime
     * @param int $smsLifetime
     * @return bool|mixed
     */
    public function sendSMS($sender, $text, $phone, $datetime = null, $smsLifetime = 0)
    {
        $smsLifetime = $smsLifetime ?: config('epochta-sms.sms_lifetime', 0);

        $res = parent::sendSMS($sender, $text, $phone, $datetime, $smsLifetime);

        if ($this->checkResult($res)) {
            return $res;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getCampaignInfo($id)
    {
        $res = parent::getCampaignInfo($id);

        if ($this->checkResult($res)) {
            return $res;
        }

        return false;
    }
}
