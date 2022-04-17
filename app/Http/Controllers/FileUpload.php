<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Backup;
use App\Models\Setting;


class FileUpload extends Controller
{
  public function createForm(){
    return view('upload.file-upload');
  }

  public function fileUpload(Request $req){
     /*$fileName = time().'_'.$req->file->getClientOriginalName();
    dd($fileName);
    $backup = new Backup; 
    $backup_dir = "/var/backups/mongobackups/cedar29Mar2022-1648555890";
    $database_name= "cedar";
    $backup->restoreBackup($backup_dir , $database_name);

    return redirect()->route('backups.index')
    ->with('success','Backup Created successfully.');
*/

          if($req->file()) {
            $fileName = "restore.gz"; //$req->file_path->getClientOriginalName();
          //  dd(trim($fileName) );
            $filePath = $req->file('file_path')->storeAs('uploads', $fileName, 'public');
         //   $tmpl_instance_path = $req->file('instance_path')->storeAs ('uploads', $tmpl_instance, 'public');
           // $fileModel->name = time().'_'.$req->file->getClientOriginalName();
            //$fileModel->vocabulary_url= $req->vocabulary_url;
            //$fileModel->file_path = 'storage/app/public/'. $filePath;
            //$fileModel->instance_path = 'storage/app/public/'. $tmpl_instance_path;
            //$fileModel->save();
            $setting = new Setting;
            $setting=$setting->getSetting();           // dd($filePath);
            $backup_dir = 'storage/app/public/'. $filePath;
          //  dd($backup_dir);
            $database_name=$setting->database_name;
                        //$database_name= "cedar";
            $backup = new Backup; 
            $backup->restoreBackup($backup_dir , $database_name);
        
            return redirect()->route('login')
            ->with('success','Backup restored successfully.');

        }
   }

}
