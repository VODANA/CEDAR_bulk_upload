<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkUpload extends Model
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

    public function bulkUpload($source_file , $secureurl , $apiKey , $templateJson){
        $inputData=$this->readCSVFile($source_file);
       // delete $templateJson["@id"];
        $templateArray = json_decode($templateJson, true);
        unset($templateArray["@id"]);

        foreach($inputData as $data ) {
            foreach($data as $field => $value ) {
                if(in_array('@value',$templateArray[$field])){
                    $templateArray[$field]['@value']=$value;         
                } elseif(in_array('@id',$templateArray[$field])){
                    $templateArray[$field]['@id']=$vocabularyUrl.trim($value);
                    $templateArray[$field]['rdfs:label']=$value;
                }
                //$templateArray['schema:name']=$data['PatientID'];
                //$templateArray['schema:description']=$_POST["field_properties"];
            }

            $input = json_encode($templateArray);  

            $this->postData($secureurl , $apiKey , $input);
       // return $input;
        }
    }

    public function postData($secureurl , $apiKey , $input){
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
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        //curl_setopt($curl, CURLOPT_PROXY, $proxy[0]);
        //curl_setopt($curl, CURLOPT_PROXYPORT, $proxy[1]);
        $uploaded = curl_exec($curl);

        curl_close($curl);
        ///return $uploaded;
    }
}
