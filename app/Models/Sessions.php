<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sessions extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'server_whatsapp',
        'apitoken',
        'session_name',
        'session_key',
        'session_api_token',

        'webhook_wh_message',
        'webhook_wh_status',
        'webhook_wh_connect',
        'webhook_wh_qr_code',

        'last_activity',
        'user_id',
        'status',
        'state',

        'last_connected',
        'last_disconnected',
        'connected',
        'locales',
        'number',
        'device_manufacturer',
        'device_model',
        'mcc',
        'mnc',
        'os_build_number',
        'os_version',
        'wa_version',
        'pushname',
        'result',
        'ip_host',
        'location',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
