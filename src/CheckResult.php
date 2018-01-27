<?php

namespace Fomvasss\EpochtaService;

trait CheckResult
{
    protected function checkResult($res)
    {
        if (isset($res['error']) || empty($res['result'])) {
            \Log::error(__METHOD__, is_array($res) ? $res : []);
        } elseif (isset($res['result'])) {
            return $res;
        }

        return;
    }
}
