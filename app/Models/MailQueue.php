<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class MailQueue extends Model 
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'activate_code'
    ];
}
