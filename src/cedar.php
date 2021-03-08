<?php
// configurtion infomation based on your cedar account
include("config.inc");

//exit(1);
//read csv and concvert it to array
function readCsv($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename)) {
        echo "Data unreadble";
        return FALSE;
    }
    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

//File upload 
 if (!file_exists($target_file)) 
  move_uploaded_file($_FILES["source_file"]["tmp_name"], $target_file);
 else {
  echo "File already exists in the server try uploading a new file or rename the file if it has new content!";
  exit(1);
 }

//Field names and values
function getFieldValues($data) {

  $data_values= "";  $schema_name="";

  foreach($data as $key => $value ) {
    $field_name='"'.$key.'"';
    if(!is_numeric($value)){
        $field_value='"'.$value.'"';
        $data_values=$data_values.$field_name.': {"@value": '.$field_value.'} ,';
    } else {
        $field_value='"'.$value.'"';
        $data_values=$data_values.$field_name.': {"@value": '.$field_value.', "@type": "xsd:decimal" } , '; 
    }

    if(!$schema_name)
       $schema_name=$value;
    }
    $datavalues[0] = $schema_name;
    $datavalues[1] = $data_values;

  return $datavalues;
}

$inputData=readCsv($target_file);

foreach($inputData as $data ) {
  $data_values=getFieldValues($data);

  if($data_values[0])
      $instance_name='"'.$_POST["description"].' '.$data_values[0].'"';
  else
      $instance_name='"'.$_POST["description"].'"';

  $field_properties=$_POST["field_properties"];
  $isBasedOn=$_POST["isBasedOn"];
  $description='"'.$_POST["description"].'"';
  $template_id='"https://repo.metadatacenter.org/templates/'.$_POST["template_id"].'"';
  $user_id='"https://metadatacenter.org/users/'.$_POST["user_id"].'"';

  //JSON-LD Based on template-instance
  $input = '{
   "@context": {
      "rdfs": "http://www.w3.org/2000/01/rdf-schema#",
      "xsd": "http://www.w3.org/2001/XMLSchema#",
      "pav": "http://purl.org/pav/",
      "schema": "http://schema.org/",
      "oslc": "http://open-services.net/ns/core#",
      "skos": "http://www.w3.org/2004/02/skos/core#",
      "rdfs:label": {
        "@type": "xsd:string"
      },
      "schema:isBasedOn": {
        "@type": "@id"
      },
      "schema:name": {
        "@type": "xsd:string"
      },
      "schema:description": {
        "@type": "xsd:string"
      },
      "pav:derivedFrom": {
        "@type": "@id"
      },
      "pav:createdOn": {
        "@type": "xsd:dateTime"
      },
      "pav:createdBy": {
        "@type": "@id"
      },
      "pav:lastUpdatedOn": {
        "@type": "xsd:dateTime"
      },
      "oslc:modifiedBy": {
        "@type": "@id"
      },
      "skos:notation": {
        "@type": "xsd:string"
      },
      '.$field_properties.'
      },'.$data_values[1].'
      "schema:isBasedOn": '.$template_id.',
      "schema:name": '.$instance_name.',
      "schema:description": '.$description.',
      "pav:createdBy": '.$user_id.',
      "oslc:modifiedBy": '.$user_id.'
    }';
    
    //URL Based on folder id
    $secureurl ="https://resource.metadatacenter.org/template-instances?folder_id=https%3A%2F%2Frepo.metadatacenter.org%2Ffolders%2F".$folder_id."";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $secureurl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: apiKey '.$apiKey,
          ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
    $uploaded = curl_exec($curl);

    curl_close($curl);

  }
  
  if($uploaded)
      echo "<center><br/>Successfully uploaded ".count($inputData)." records to CEDAR!</center>";
?>



