<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use File;
use ZipArchive;

class Backup extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'backups';
    protected $fillable = [
        'backup_name',
        'description',
        'backup_dir',
        'database_name',
    ];

    public function createBackup($backup_dir='', $database='') {
      //  dd($backup_dir);
        $backup_path=$backup_dir."/".date('dMY');
        $command = "mongodump --db ".$database." --gzip -o ".$backup_path;
       // $command = "mongodump --db ".$database." --out ".$backup_dir."/Backup-".date('dMY');    
       // dd($command);
        $dumped=exec($command);
        return $dumped;
    }

    public function zipBackup($backup_dir ,  $database_name) {

        $zip = new ZipArchive;
        $backup_path=$backup_dir."/".date('dMY');
      //  dd($backup_path);
        $fileName = $database_name.date('dMY').'.gz';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files($backup_path.'/'.$database_name);
            // $files = File::files(public_path('myFiles'));
            // dd($files);
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        $store_in_path= "cp $fileName $backup_dir";
       // dd($backup_dir);
      //  dd($store_in_path);
        exec($store_in_path);
        

      return response()->download(public_path($fileName));

    }
    public function restoreBackup($backup_dir='', $database='') {
     //   dd('here'.$database);
       // $path=base_path("storage/app/public/uploads/cedar06Apr2022-1649251643");
     //   dd('here'.$backup_dir);
        //$command= "mongorestore --drop --db ".$database." ".base_path($backup_dir);
       /* $path=base_path($backup_dir);
        $command= "unzip ".$path;
        $restored=exec($command);
        dd($command);*/
        $setting = new Setting;
        $setting = $setting->getSetting();

        if($setting->backup_path ) {
            $backup_path=$setting->backup_path;
            $database=$setting->database_name;
        } else {
            $backup_path="/var/backups/mongobackups";
            $database="cedar";
        }

        $command= "unzip ".base_path($backup_dir)." -d ".$backup_path."/".$database;

        $restored=exec($command);
     //   dd('here: '.$command);

      //  $command= "unzip ".$backup_path;

    //dd('here'.$command);

        $command1= "mongorestore --drop --db ".$database." --gzip --db ".$database." ".$backup_path."/cedar";//.$database;
        //  $command= "mongorestore --drop --db ".$database." --gzip --archive=".base_path($backup_dir);

        $command2= "rm ".$backup_path."/".$database."/*";
      //  dd($command2);
        $command = $command1." && ".$command2;
     //   dd($command1);
        $restored=exec($command);
     //   dd($command);
        return $restored;
    }

}