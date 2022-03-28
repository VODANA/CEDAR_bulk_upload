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
        $backup_path=$backup_dir."/Backup-".date('dMY');
        $command = "mongodump --db ".$database." --out ".$backup_dir."/Backup-".date('dMY');    
        $dumped=exec($command);
        return $dumped;
    }

    public function zipBackup($backup_dir ,  $database_name) {

        $zip = new ZipArchive;
        $backup_path=$backup_dir."/Backup-".date('dMY');
        $fileName = $database_name.date('dMY').'-'.time().'.zip';
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

        $store_in_path= "cp $fileName $backup_dir && rm -R $backup_path";

        exec($store_in_path);
        

      return response()->download(public_path($fileName));

    }
    public function restoreBackup($backup_dir='', $database='') {
        $command= "mongorestore --drop --db ".$database." ".$backup_dir."/".$database;
        $restored=exec($command);
       // dd($command);
        return $restored;
    }

}