<?php

namespace Fomvasss\EpochtaService\Models;

use Illuminate\Database\Eloquent\Model;

class EpochtaSms extends Model
{
    protected $table = 'epochta_sms';

    protected $fillable = [
        'sender',
        'phone',
        'body',
        'datetime',
        'lifetime',

        'sms_id',
        'sms_sent_status',
        'sms_delivered_status',
        'dispatch_status',

        'resend_sms_id',
        'attempt',
    ];
}
