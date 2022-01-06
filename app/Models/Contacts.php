<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Contacts extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "avatar",
        "phone",
        "name",
        "isBusiness",
        "user_id",
        "group_id",
        "imported",
        "tag_id",
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Messages', 'number', 'phone')->with('user');
    }

    public function group()
    {
        return $this->hasOne('App\Models\Groups', 'id', 'group_id');
    }

    public function messagesLight()
    {
        return $this->hasMany('App\Models\Messages', 'number', 'group_id')
        ->select('name', 'number', 'message', 'user_id', 'status', 'type', 'send_at')
        ->with('user');
    }

    public function messagesContact()
    {
        return $this->hasMany('App\Models\Messages', 'number', 'phone')
        ->select('id', 'user_id', 'status', 'message', 'type', 'number', 'send_at');
    }

    public function messagesCount()
    {
        return $this->hasMany('App\Models\Messages', 'user_id', 'user_id');
    }

    public function tag()
    {
        return $this->hasOne('App\Models\Tags', 'id', 'tag_id');
    }

}
