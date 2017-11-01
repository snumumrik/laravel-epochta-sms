<?php

namespace Fomvasss\EpochtaService;

/**
 * Class State
 *
 * @package \Fomvasss\EpochtaService
 */
class Account extends Libraries\Account
{

    use CheckResult;
    
    public function getUserBalance($currency = null)
    {
        $currency = $currency ?: config('epochta-sms.currency', 'USD');

        $res = parent::getUserBalance($currency);

        if ($this->checkResult($res)) {
            return $res;
        }
        
        return 0;
    }
}
