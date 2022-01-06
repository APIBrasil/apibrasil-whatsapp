<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'number',
        'message',
        'user_id',
        'status',
        'path',
        'type',
        'send_at',
        'schedule',
        'date_schedule_send',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function group()
    {
        return $this->hasOne('App\Models\Groups', 'group_id', 'number');
    }

    public function contact()
    {
        return $this->hasOne('App\Models\Contacts', 'phone', 'number');
    }

}
