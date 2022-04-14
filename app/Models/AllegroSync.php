<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllegroSync extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'file_path',
        'instance_path',
        'vocabulary_url',
        'folder_id'
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

    public function bulkSync($source_file , $secureurl , $apiKey , $vocabularyUrl, $templateJson){
         $inputData=$this->readCSVFile($source_file);
         // delete $templateJson["@id"];
         $templateArray = json_decode($templateJson, true);
         $template_id=$templateArray["@id"];
         unset($templateArray["@id"]);
         $rdf="";
         foreach($inputData as $data ) {
             foreach($data as $field => $value ) {
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