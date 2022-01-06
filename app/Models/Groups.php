<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'avatar',
        'link',
        "size",
        "id",
        "owner",
        "subject",
        "creation",
        "subject_time",
        "subject_owner",
        "restrict",
        "ephemeral_duration",
        "desc",
        "desc_id",
        "desc_time",
        "desc_owner",
        'group_id',
        'user_id',
        'tag_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function messagesLight()
    {
        return $this->hasMany('App\Models\Messages', 'number', 'group_id')
        ->select('name', 'number', 'message', 'user_id', 'status', 'type', 'send_at')
        ->with('user');
    }

    public function tags()
    {
        return $this->hasOne('App\Models\Tags', 'id', 'tag_id');
    }

    public function messagesGroups()
    {
        return $this->hasMany('App\Models\Messages', 'number', 'group_id')
        ->select('name', 'number', 'message', 'user_id', 'status', 'type', 'send_at');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Messages', 'number', 'group_id')->with('user');
    }
}
