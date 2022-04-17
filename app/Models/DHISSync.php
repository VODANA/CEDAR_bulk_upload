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
   
  /*** ***************************************************
       ***************************************************
        Compute Indicator for ANC based on JSON Metadata
        mmask uploaded by the user
        ************************************************ ***/

  public function getIndicatorValue($var,$varValue,$compare,$filterVar,$filterCriteria,$filterValue,$min,$max,$filterDate) {
      
      if(!$min && !$filterVar)
          return DHISSync::whereRaw([$var => [$compare => $varValue]])
              ->where(['schema:name' => ['$regex' => 'Antenatal']])
              ->where([$filterDate => ['$gte' => $this->getStartDateOfLastMonth(), '$lte' => $this->getEndDateOfLastMonth()]])
              ->count();
      elseif(!$min && $filterVar)
          return DHISSync::whereRaw([$var => [$compare => $varValue]])
              ->where($filterVar, $filterCriteria, $filterValue )
              ->where(['schema:name' => ['$regex' => 'Antenatal']])
              ->where([$filterDate => ['$gte' => $this->getStartDateOfLastMonth(), '$lte' => $this->getEndDateOfLastMonth()]])
              ->count();//->where('pav:createdOn', ">=", $report_month)->count();
      elseif($min && $filterVar)
          return DHISSync::whereRaw([$var => ['$gt' => $min, '$lt' => $max]])
              ->where($filterVar, $filterCriteria, $filterValue)
              ->where(['schema:name' => ['$regex' => 'Antenatal']])
              ->where([$filterDate => ['$gte' => $this->getStartDateOfLastMonth(), '$lte' => $this->getEndDateOfLastMonth()]])
              ->count();
      elseif($min && !$filterVar)
          return DHISSync::whereRaw([$var => ['$gt' => $min, '$lt' => $max]])
              ->where(['schema:name' => ['$regex' => 'Antenatal']])
              ->where([$filterDate => ['$gte' => $this->getStartDateOfLastMonth(), '$lte' => $this->getEndDateOfLastMonth()]])
              ->count();
      else
          return DHISSync::all()->count();
  }

  /*** ***************************************************
      ***************************************************
      Sync CEDAR data fetched from MongoDB and compute hmis 
      indicator for ANC based on JSON Metadata mask uploaded by the user
      ************************************************************* ***/

  public function syncDHIS2($dhis_data_elements, $setting){
      //check if dhis setting are filled
      if($setting->hmis_username && $setting->hmis_password) {
          $pass=base64_decode($setting->hmis_password);  
          $auth=base64_encode($setting->hmis_username.":".$pass);
      } else {
        $notification = array(
          'message' => "HMIS Setting not yet set.Provide the necessary cridentials",
          'alert-type' => 'error'
         );
      return -1;

      }
      
      $content_type='text/plain';

      foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
            $dataelement->value=$this->getIndicatorValue($dataelement->basedOn,$dataelement->cutPoint,$dataelement->comparison,$dataelement->filterVar,$dataelement->filterLogic,$dataelement->filterCutPoint,$dataelement->min,$dataelement->max,$dataelement->filterDate);
            $dataelement->period=date("Y").date("m")-1;
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $setting->hmis_url.'/api/dataValues?de='.$dataelement->dataElement.'&pe='.$dataelement->period.'&ou='.$dataelement->orgUnit.'&co='.$dataelement->categoryOptionCombo.'&value='.$dataelement->value,
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

public function getVar($var){
    $new_var=stripcslashes($var);
}

public function syncDHIS_json($dhis_data_elements , $anc, $anc_json){
      $setting = new Setting;
      $setting = $setting->getSettings(auth()->id());

      $pass=base64_decode($setting->hmis_password);  
      $auth=base64_encode($setting->hmis_username.":".$pass);

      $content_type='application/json';
      $secureurl = 'http://localhost:8085/api/dataValues';
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $secureurl);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              'Content-Type: '.$content_type,
              'Accept: '.$content_type,
              'Authorization: Basic '.$auth
            ));
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $anc_json);
      //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
      //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
      $uploaded = curl_exec($curl);
      curl_close($curl);
  }

  // Get Beginning of Last Month
  public function getStartDateOfLastMonth(){
      $year=date("Y");
      $month=date("m");
      if(($month-01)==0) {
        $year=$year-1;
        $month=12;
      } 
      else
        $month=$month-00;          ;

      $dt= $year.'-'.$month.'-'.date("d");
      $start_date=date("Y-m-01 00:00:00", strtotime($dt));
    return $start_date; 
   } 

  // Get End of Last Month

  public function getEndDateOfLastMonth(){
    $year=date("Y");
    $month=date("m");
    if(($month-01)==0) {
      $year=$year-1;
      $month=12;
    } 
    else
      $month=$month-00;          ;

    $dt= $year.'-'.$month.'-'.date("d");
    $last_date=date("Y-m-t", strtotime($dt));
  return $last_date;
  }
  
  //Delete Template mask after use
  public function cleanAfterUse($path){
        $command="rm ".$path;
        $del=exec($command);
    return $del;
  }
}