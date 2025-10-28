<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Jobs\SendAccountPasswordResetEmail;
use App\Notifications\AccountEmailResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $token = URL::to('/') .'/reset-password/'.$token;
        $this->notify(new AccountEmailResetNotification($token));
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'parent_id', 'id');
    }
    public function orders(){
        return $this->hasMany(Order::class,'user_id','id');
    }
    public function ordersToPay(){
        return $this->hasMany(Order::class,'user_id','id')->where('status','order_created');
    }
}
