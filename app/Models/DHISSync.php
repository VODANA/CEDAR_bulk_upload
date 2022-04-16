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
	protected $collection = 'template-instances';
   /* protected $fillable = [
        'backup_name',
        'description',
        'backup_dir',
        'database_name',
    ]; */
    //Get ANC MonthlyReport
    public function getANCMonthlyReport(){
            $anc[0] = DHISSync::whereRaw(['GestetionalAge.@value' => ['$lt' => 16]])->get();
            $anc[1] = DHISSync::whereRaw(['GestetionalAge.@value' => ['$gt' => 15]])->get();
            $anc[2] = DHISSync::whereRaw(['Age.@value' => ['$gt' => 9, '$lt' => 15]])->get();
            $anc[3] = DHISSync::whereRaw(['Age.@value' => ['$gt' => 14, '$lt' => 20]])->get();
            $anc[4] = DHISSync::whereRaw(['Age.@value' => ['$gt' => 19]])->get();
            $anc[5] = DHISSync::whereRaw(['Age.@value' => ['$gt' => 9, '$lt' => 15]])->where('VisitNumber.@value', ">=", 4)->get();
            $anc[6] = DHISSync::whereRaw(['Age.@value' => ['$gt' => 14, '$lt' => 20]])->where('VisitNumber.@value', ">=", 4)->get();
            $anc[7] = DHISSync::whereRaw(['Age.@value' => ['$gt' => 19]])->where('VisitNumber.@value', ">=", 4)->get();
        return $anc;
    }
    public function getIndicatorValue($var,$varValue,$compare,$filterVar,$filterCriteria,$filterValue,$min,$max) {
        if(!$min && !$filterVar)
            return DHISSync::whereRaw([$var => [$compare => $varValue]])->where(['schema:name' => ['$regex' => 'Antenatal']])->count();
        elseif(!$min && $filterVar)
            return DHISSync::whereRaw([$var => [$compare => $varValue]])->where($filterVar, $filterCriteria, $filterValue )->where(['schema:name' => ['$regex' => 'Antenatal']])->count();//->where('pav:createdOn', ">=", $report_month)->count();
        elseif($min && $filterVar)
            return DHISSync::whereRaw([$var => ['$gt' => $min, '$lt' => $max]])->where($filterVar, $filterCriteria, $filterValue)->where(['schema:name' => ['$regex' => 'Antenatal']])->count();
        elseif($min && !$filterVar)
            return DHISSync::whereRaw([$var => ['$gt' => $min, '$lt' => $max]])->where(['schema:name' => ['$regex' => 'Antenatal']])->count();
        else
            return DHISSync::all()->count();
    }


    //[]******************************************************
   //// ***************************************************
//$base_string = base64_encode("your-string"); //for base64 encoding
//echo base64_decode($base_string); //for base64 decoding
//*********************************************************************** */

    //Sync to DHIS2
    /*public function syncDHIS($dhis_data_elements , $anc){
        foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
            $dataelement->value=$anc[$key]->count();
            $dataelement->period="202202"; //date("Ym");
           // $value=$dataelement->value;
            $secureurl='http://localhost:8085/api/dataValues?de='.$dataelement->dataElement.'&ou='.$dataelement->orgUnit.'&pe='.$dataelement->period.'&co='.$dataelement->categoryOptionCombo.'&value='.$dataelement->value;
          //  dd($secureurl);
            $content_type='text/plain';
           // $api_key='BLPdhZ90uMf8q4';
            $curl = curl_init();
            curl_setopt_array($curl, array($secureurl, 
            CURLOPT_SSL_VERIFYPEER =>false, 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST', 
            CURLOPT_HTTPHEADER => array('Autherization: Basic'),
            CURLOPT_USERPWD => 'admin:12345678@A')); 
            //curl_setopt($curl, CURLOPT_POSTFIELDS, "value=$value");
            //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
            //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
            $rdf_uploaded = curl_exec($curl);
            curl_close($curl);
        }
        dd($rdf_uploaded);

    return $rdf_uploaded;
    
    }  */

    public function syncDHIS($dhis_data_elements , $anc, $anc_json){
      $setting = new Setting;
      $setting = $setting->getSettings(auth()->id());
      $content_type='text/plain';
  
      $pass=base64_decode($setting->hmis_password);  
      $auth=base64_encode($setting->hmis_username.":".$pass);
       // dd($dhis_data_elements->dataValues[0]->dataElement);
        foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
            $dataelement->value=$anc[$key]->count();
            $dataelement->period="202202"; //date("Ym");
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8085/api/dataValues?de='.$dataelement->dataElement.'&pe='.$dataelement->period.'&ou='.$dataelement->orgUnit.'&co='.$dataelement->categoryOptionCombo.'&value='.$dataelement->value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
              'Authorization: Basic '.$auth
            ),

          ));
        $uploaded = curl_exec($curl);
    } 
        return $uploaded;
  
  }
  public function syncDHIS3($dhis_data_elements , $anc, $anc_json){
  $setting = new Setting;
  $setting = $setting->getSettings(auth()->id());
  // dd($dhis_data_elements->dataValues[0]->dataElement);
   foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
      // dd($dataelement->comparison);
     //  dd($dataelement->cutPoint);
       $dataelement->value=$this->getIndicatorValue($dataelement->basedOn,$dataelement->cutPoint,$dataelement->comparison,$dataelement->filterVar,$dataelement->filterLogic,$dataelement->filterCutPoint,$dataelement->min,$dataelement->max);
       $dataelement->period=date("Y").date("m")-1;
       $content_type='text/plain';

      $secureurl = $setting->hmis_url.'api/dataValues?de='.$dataelement->dataElement.'&pe='.$dataelement->period.'&ou='.$dataelement->orgUnit.'&co='.$dataelement->categoryOptionCombo.'&value='.$dataelement->value;
     // dd($secureurl);

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
      curl_setopt($curl, CURLOPT_USERNAME, $setting->hmis_username);
      curl_setopt($curl, CURLOPT_PASSWORD, base64_decode($setting->hmis_password));
     // curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
      //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
      //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
      $uploaded = curl_exec($curl);
     // dd($rdf_uploaded);
      curl_close($curl);

   //dd($uploaded);
} 
   return $uploaded;

}
  public function syncDHIS2($dhis_data_elements , $anc, $anc_json){
    $setting = new Setting;
    $setting = $setting->getSettings(auth()->id());
    $content_type='text/plain';

    $pass=base64_decode($setting->hmis_password);  
    $auth=base64_encode($setting->hmis_username.":".$pass);
  //  dd($aa);
  //  dd(base64_decode('YWRtaW46MTIzNDU2NzhAQQ=='));
    // dd($dhis_data_elements->dataValues[0]->dataElement);
     foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
        // dd($dataelement->comparison);
       //  dd($dataelement->cutPoint);
         $dataelement->value=$this->getIndicatorValue($dataelement->basedOn,$dataelement->cutPoint,$dataelement->comparison,$dataelement->filterVar,$dataelement->filterLogic,$dataelement->filterCutPoint,$dataelement->min,$dataelement->max);
         $dataelement->period=date("Y").date("m")-1;
         $curl = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_URL => 'http://localhost:8085/api/dataValues?de='.$dataelement->dataElement.'&pe='.$dataelement->period.'&ou='.$dataelement->orgUnit.'&co='.$dataelement->categoryOptionCombo.'&value='.$dataelement->value,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
       //  CURLOPT_USERNAME => $setting->hmis_username,
       //  CURLOPT_PASSWORD => base64_decode($setting->hmis_password),
     //    CURLOPT_HTTPHEADER, array(
      //      'Content-Type: '.$content_type,
     //   )
         CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '.$auth
          ),
       ));
     $uploaded = curl_exec($curl);
    // dd($uploaded);
 } 
     return $uploaded;

}

  public function getVar($var){
      $new_var=stripcslashes($var);
  }

  public function syncDHIS_json($dhis_data_elements , $anc, $anc_json){
        $content_type='application/json';
        $secureurl = 'http://localhost:8085/api/dataValues';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $secureurl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: '.$content_type,
                'Accept: '.$content_type,
                'Authorization: Basic YWRtaW46MTIzNDU2NzhAQQ==',
                'Cookie: JSESSIONID=0668FC706E5BEB6CFD45BAA67C1D88E4'
            ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $anc_json);
        //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
        //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
        $uploaded = curl_exec($curl);
        curl_close($curl);
        dd($uploaded);
    }
   
}