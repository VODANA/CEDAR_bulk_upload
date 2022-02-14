<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'url',
        'api_token',
        'folder_id',
        'location',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getSettings($user_id) {
        return $this->where('user_id',$user_id)->first();
    }
}