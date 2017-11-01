<?php

namespace Fomvasss\EpochtaService\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class EpochtaSms extends Model
{
    public $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('epochta-sms.sms_db_table_name', 'epochta_sms'));
    }
}
