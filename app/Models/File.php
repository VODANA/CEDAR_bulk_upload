<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    protected $connection = 'mongodb';
	protected $collection = 'backups';

    use HasFactory;
   /* protected $fillable = [
        'name',
        'file_path',
        'instance_path',
        'vocabulary_url'
    ];*/
}
