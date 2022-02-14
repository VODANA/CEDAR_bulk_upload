<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = [

        'name', 'description',  'file_path', 'folder_id'

    ];
    public function createTemplate($secureurl,$apiKey,$templateJson){
        // $proxy = "127.0.0.1:8080";
        // $proxy = explode(':', $proxy);
        $ch = curl_init();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $secureurl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: apiKey '.$apiKey,
            ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $templateJson);
        //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
        //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
        $uploaded = curl_exec($curl);
        curl_close($curl);

      return $uploaded;
    }
}
