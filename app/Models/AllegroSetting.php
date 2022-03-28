<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class AllegroSetting extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'allegro_settings';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'username','password'.'api_key'
    ];
}