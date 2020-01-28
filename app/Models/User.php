<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Carbon\Carbon;

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
        'api_token',
        'is_active',
        'token_expire_at'
    ];

    static function hash($val) {
        return md5($val);
    }

    static function generateApiToken() {
        return self::hash(rand(1,9999) . time());
    }

    static function createUser($props = array()) {
        $user = self::create([
            'email' => $props['email'],
            'password' => self::hash($props['password']),
            'api_token' => self::generateApiToken(),
            'is_active' => 0
        ]);

        $email = MailQueue::create(['email' => $user->email, 
            'activate_code' => $user->api_token]);

        return $email;
    }

    static function activate($code) {
        $user = self::where('api_token', $code)->first();
        if ($user) {
            $user->is_active = 1;
            $user->save();
        }

        return $user;
    }

    static function getByEmail($email) {
        return self::where('email', $email)->first();
    }

    static function auth($email, $password) {
        $user = self::where([ 
                'email' => $email, 
                'password' => self::hash($password),
                'is_active' => 1
            ])->first();

        if ($user) {
            $user->token_expire_at = (new Carbon())->addDays(1)->toDateTimeString();
            $user->api_token = self::generateApiToken();
            $user->save();
        }

        return $user;
    }
}

