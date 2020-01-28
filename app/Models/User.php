<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class User extends Model 
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'api_token'
    ];

    static function hash($val) {
        return md5($val);
    }

    static function createUser($props = array()) {
        MailQueue::create(['email' => $user.email, 
            'activate_code' => $user.api_token]);

        $user = self::create([
            'email' => $props['email'],
            'password' => self::hash($props['password']),
            'api_token' => self::hash(time())
        ]);


        return $user;
    }
}
