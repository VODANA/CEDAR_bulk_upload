<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use File;
use ZipArchive;

class DHISSync extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'anc';
   /* protected $fillable = [
        'backup_name',
        'description',
        'backup_dir',
        'database_name',
    ]; */
    //Get ANC MonthlyReport
    public function getANCMonthlyReport(){
            $anc[0] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$lt' => 16]])->get();
            $anc[1] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$gt' => 15]])->get();
            $anc[2] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 9, '$lt' => 15]])->get();
            $anc[3] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 14, '$lt' => 20]])->get();
            $anc[4] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 19]])->get();
            $anc[5] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 9, '$lt' => 15]])->where('VisitNumber.@value', ">=", 4)->get();
            $anc[6] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 14, '$lt' => 20]])->where('VisitNumber.@value', ">=", 4)->get();
            $anc[7] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 19]])->where('VisitNumber.@value', ">=", 4)->get();
        return $anc;
    }

    //Sync to DHIS2
    public function syncDHIS($dhis_data_elements , $anc){
        foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
            $dataelement->value=$anc[$key]->count();
            $dataelement->period=date("Ym");
            $value=$dataelement->value;
            $secureurl='http://192.168.0.183:8082/api/dataValues?de=?de=$dataelement->dataElement&ou=$dataelement->orgUnit&pe=$dataelement->period';
            $content_type='text/plain';
            $api_key='BLPdhZ90uMf8q4';
            $ch = curl_init();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $secureurl);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: '.$content_type,
                    'Accept: '.$content_type,
                ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_USERNAME, '103050025_PHCU_hit');
            curl_setopt($curl, CURLOPT_PASSWORD, '_e8QE6RTV#');
            curl_setopt($curl, CURLOPT_POSTFIELDS, "value=$value");
            //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
            //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
            $rdf_uploaded = curl_exec($curl);
        // dd($rdf_uploaded);
            curl_close($curl);
        }
    return $rdf_uploaded;
    }  
  
}