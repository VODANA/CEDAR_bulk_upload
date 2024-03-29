<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'setting';
    protected $fillable = [
        'site_name',
        'url',
        'api_token',
        'folder_id',
        'location',
        'user_id',
        'hmis_username',
        'hmis_password',
        'hmis_url',
        'allegro_username',
        'allegro_password',
        'allegro_url',
        'backup_path',
        'database_name',
    ];
   /* public function user()
    {
        return $this->belongsTo(User::class);
    }*/
    public function getSettings($user_id) {
        return $this->where('user_id',$user_id)->first();
    }
    public function getSetting() {
        return $this->first();
    }
}