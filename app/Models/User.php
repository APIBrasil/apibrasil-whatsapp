<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Sessions;
use App\Models\Messages;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'server_whatsapp',
        'cellphone',
        'cpf',
        'last_login',
        'last_activity',
        'last_ip',
        'block_mail',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contacts()
    {
        return $this->hasMany(Contacts::class, 'user_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Groups::class, 'user_id', 'id');
    }

    public function sessions()
    {
        return $this->hasMany(Sessions::class, 'user_id', 'id');
    }

    public function messagesNaoEnviadas()
    {
        return $this->hasMany(Messages::class, 'user_id', 'id')
        ->where('status', 'Aguardando');
    }

    public function messages()
    {
        return $this->hasMany(Messages::class, 'user_id', 'id');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
