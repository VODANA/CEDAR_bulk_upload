<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class TemplateInstance extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'template-instances';

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
    public function getTemplate($title){
        //return Template::where('schema:name', '%like%', $title )->get();
        return Template::whereRaw(['schema:name' => ['$regex' => $title]])->get();
    }

    // To be taken to DHISSync Model
    public function getDataElements($dataElementsJson){
        $dataElements=array();
        $dataElementsObj=json_decode($dataElementsJson)->dataValues;
        foreach($dataElementsObj as $key => $value) {
           // dd($value->dataElement);
            if(!array_key_exists($value->dataElement, $dataElements)) 
                $dataElements[$value->dataElement] = $value->dataElementDescription;
        }
        return $dataElements;
    }

    public function getTemplateInstances($title){
        //return Template::where('schema:name', '%like%', $title )->get();
        return SyncToAllegro::whereRaw(['schema:name' => ['$regex' => $title]])->get();
    }
}
