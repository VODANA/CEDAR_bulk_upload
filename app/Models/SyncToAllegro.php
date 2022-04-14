<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class SyncToAllegro extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'template-instances';
    protected $fillable = [
        'rdf',
        /*"Health_Facility_name", 
        "Date_of_Patient_Visit" ,
        "Client_Category" ,
        "Reason_for_admission" ,
        "Live_birth" ,
        "Delivered_by" ,
        "Date_of_discharge" ,
        "Mode_of_delivery" ,
        "Condition_of_mother_at_discharge" ,
        "Expected_date_of_delivery" ,
        "Gender_of_baby" ,
        "Weight_of_baby" ,
        "Mother_name" ,
        "Father_name" ,
        "Reporting_country" ,
        "Condition_of_father" ,
        "Next_of_kin_number" ,
        "Next_of_kin_name" ,
        "Sequential_Serial_Number" ,
        "Marital_status" ,
        "Mother's_level_of_education" ,
        "Condition_of_mother_on_admission",*/
    ];

    public function readCSVFile($source_file){
        $header = NULL;
        $inputData = array();
        $delimiter=',';
        if (($handle = fopen(base_path($source_file), 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $inputData[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $inputData;
    }
    public function getTemplateInstances($title){
      //return Template::where('schema:name', '%like%', $title )->get();
      return SyncToAllegro::whereRaw(['schema:name' => ['$regex' => $title]])->get();
    }
  //  public function bulkSync($source_file , $secureurl , $apiKey , $vocabularyUrl, $templateJson){
    public function bulkSync($templateJson){
       // dd($templateJson);

        // delete $templateJson["@id"];
        $templateArray = json_decode($templateJson, true);
       
        // unset($templateArray["@id"]);
        //unset($templateArray["_id"]);
        // $templateArray = json_encode($templateJson, true);
        $rdf="";
        foreach($templateArray as $key=>$value ) {
          //  echo $key;
       //   dd($templateArray);
            //echo "<br/>";
            if(array_key_exists('@id', $templateArray)){
              $template_id=$templateArray["@id"];
      
              //  continue;
              if($key!='@context' && is_array($value)) {
                    //  dd($templateArray['@context']);
                      //   echo $templateArray['@context'][$key]['@value'];
                      if(array_key_exists('@id', $value)) {
                          $rdf_new=$this->createRDF($template_id,$templateArray['@context'][$key],$value['@id']);
                          $rdf=$rdf." ".$rdf_new;
                      }
                      elseif(array_key_exists('@value', $value)){
                          $rdf_new=$this->createRDF($template_id,$templateArray['@context'][$key],$value['@value']);
                          $rdf=$rdf." ".$rdf_new;
                      }
               }
            } 
          }
          //  $rdf_context=$this->getRDFContextVars($template_id,$templateArray,$value);
          //  $rdf=$rdf_context." ".$rdf;
          //  $input = json_encode($templateArray);  
             
          //$this->postData($secureurl , $apiKey , $input);
          $secureurl="";  $apiKey='';
          if($rdf)
              $this->postToAllegro($secureurl , $apiKey , $rdf);
              
        // return $input;
         
    }
    public function createJsonLDInstance($inputData , $secureurl='' , $apiKey='' , $vocabularyUrl='', $templateJson){
       // dd($templateJson );
        $templateArray = json_decode($templateJson, true);
        $template_id=$templateArray["@id"];
        unset($templateArray["@id"]);
        $rdf="";
        foreach($inputData as $field => $value ) {
               if(array_key_exists('@value', $templateArray[$field])){
                   $templateArray[$field]['@value']=$value;         
               } elseif(array_key_exists('@id', $templateArray[$field])){
                   $templateArray[$field]['@id']=$vocabularyUrl.trim($value);
                   $templateArray[$field]['rdfs:label']=$value;
               }
               $rdf_new=$this->createRDF($template_id,$templateArray['@context'][$field],$value);
               $rdf=$rdf." ".$rdf_new;
                //$templateArray['schema:name']=$data['PatientID'];
                //$templateArray['schema:description']=$_POST["field_properties"];
           }
            $rdf_context=$this->getRDFContextVars($template_id,$templateArray,$value);
            $rdf=$rdf_context." ".$rdf;
          //  dd($rdf);

            $input = json_encode($templateArray);  
            
            $this->postData($secureurl , $apiKey , $input);
            $this->postToAllegro($secureurl , $apiKey , $rdf);
            
       // return $input;
        
   }
    public function getRDFContextVars($template_id, $templateArray, $field_value) { 
          $context_var="";
          $context_modifiedBy='<'.$template_id.'> <'.$templateArray['@context']['oslc'].'modifiedBy> "'.$templateArray['oslc:modifiedBy'].'" . ';
          $context_createdBy='<'.$template_id.'> <'.$templateArray['@context']['pav'].'createdBy> "'.$templateArray['oslc:modifiedBy'].'" . ';
          $context_createdOn='<'.$template_id.'> <'.$templateArray['@context']['pav'].'createdOn> "'.$templateArray['@context']['xsd'].'dateTime'. '" .';
          $context_lastUpdatedOn='<'.$template_id.'> <'.$templateArray['@context']['pav'].'lastUpdatedOn> "'.$templateArray['@context']['xsd'].'dateTime'. '" .';
          $context_description='<'.$template_id.'> <'.$templateArray['@context']['schema'].'description> "'.$templateArray['schema:description'].'" . ';
          $context_isBasedOn='<'.$template_id.'> <'.$templateArray['@context']['schema'].'isBasedOn> "'.$templateArray['schema:isBasedOn'].'" . ';
          $context_var=$context_var.$context_modifiedBy.$context_createdBy.$context_createdOn.$context_lastUpdatedOn.$context_description.$context_isBasedOn;
        return $context_var;  
    }    
    public function createRDF($template_id, $field_property, $field_value) {
        return '<'.$template_id.'> <'.$field_property.'> "'.$field_value.'" . ';
    }
    public function createBackup($backup_dir='', $database='') {
        // $command = "sudo mongodump --username 'admin' --password 'letmein' --db ".$database." --out ".$backup_dir."/Backup-".time();   
        $command = "mongodump --db ".$database." --out ".$backup_dir."/Backup-".date('DMY')."-".time();    
        // $command = "mongodump --host=127.0.0.1 --port=27017 --authenticationDatabase='cedar' -u='admin' -p='letmein' --db ".$database." --out ".$backup_dir."/Backup-".time();
        // $backUpCommand = "mongodump --archive='/var/backups/mongobackups' --db=cedar";
        $dumped=exec($command);
        dd($dumped);
        // dd($backUpCommand);
        // dd(shell_exec($command));
        // return $command;
    }
    public function postData($secureurl , $apiKey , $input){
        $content_type='application/json';
        $ch = curl_init();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $secureurl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: '.$content_type,
                'Accept: '.$content_type,
                'Authorization: apiKey '.$apiKey,
            ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
        //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
        $uploaded = curl_exec($curl);
      //  dd($uploaded);
        curl_close($curl);
      return $uploaded;
    }
    public function postToAllegro($secureurl , $apiKey , $input){
        $secureurl="http://localhost:10035/repositories/Covid/statements";
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
        curl_setopt($curl, CURLOPT_USERNAME, 'admin');
        curl_setopt($curl, CURLOPT_PASSWORD, 'letmein');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
        //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
        $rdf_uploaded = curl_exec($curl);
       // dd($rdf_uploaded);
        curl_close($curl);
      return $rdf_uploaded;
    }  

}