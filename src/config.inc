<?php
//API Key for CEDAR
$apiKey=$_POST['token'];

//Data source to be upoaded
$source_file =$_POST['source_file']; 
 
//Target Directory 
$target_dir = "../data/";
$target_file = $target_dir . basename($_FILES["source_file"]["name"]);

$folder_id=$_POST['folder_id'];