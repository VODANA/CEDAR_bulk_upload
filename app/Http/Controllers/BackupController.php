<?php


namespace App\Http\Controllers;


use App\Models\Backup;
use Illuminate\Http\Request;
use App\Models\Setting;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $backup = new Backup;
        $backup_dir="/var/backups/mongobackups/Backup-MonMar2022-1647877677";
        $database="cedar";
        $backup->restoreBackup($backup_dir, $database);        
       //return view('backup.create');

      

     /*    $setting = new Setting;
        $setting=$setting->getSettings(auth()->id());
        $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2Fallegrosyncfolderid"; //Folder Id
        //Read template instance
        // $templateJson = file_get_contents(base_path($allegrosync->instance_path));
        // $templateJson=$synctoallegros[0];

       // dd($templateJson);
        $b = new SyncToAllegro;
        for($k=0; $k < count($synctoallegros)-1; $k++) {
            $b->bulkSync($synctoallegros[$k]);
        }
      //  dd($stats);
        //return redirect()->route('allegrosyncs.index')->with($notification);

        //$users = Book::where('Sex','Male')->get();
        //dd($instances);
        //$books=json_decode($books);
*/
        return view('backup.index',compact('backup'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function restoreDb(){
        return view('backup.restore');
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       return view('backup.create');
    }


    public function restore(Request $request)
    {
          /*  $backup = new Backup;
            $backup_dir = "/var/backups/mongobackups/Backup-MonMar2022-1647877591/cedar";
            $database_name= "cedar";
            $backup->restoreBackup($backup_dir , $database_name);

        return redirect()->route('backups.index')
        ->with('success','Backup Created successfully.');*/
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      /*  $s = new SyncToAllegro;
        $setting = new Setting;
        $setting=$setting->getSettings(auth()->id());
        $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2Fallegrosyncfolderid"; //Folder Id
        $apiKey = $setting->api_key;
        // $s->save($request->all());
        // $s->postToAllegro($secureurl , $apiKey , $request->rdf);
        $instance_path="storage/app/public/uploads/1646548548_lumc.json";
        $templateJson = file_get_contents(base_path($instance_path));
        $s->rdf=$templateJson;
        $s->save();

        return redirect()->route('synctoallegros.index')
                        ->with('success','Instamces synced to AllegroGraph successfully.');
        */
       // dd($request->backup_dir->getClientOriginalPath());
        if($request->backup_dir)
            $backup_dir = $request->backup_dir;
        else
            $backup_dir = "/var/backups/mongobackups";

        if($request->database_name)
            $database_name = $request->database_name;
        else
            $database_name= "cedar";
        
        $backup = new Backup;
        $backup_dir = "/var/backups/mongobackups";///cedar19Mar2022-1647723066.zip";
        $backup_created=$backup->createBackup($backup_dir , $database_name);
        $zipped=$backup->zipBackup($backup_dir , $database_name);
        
        return $zipped;

    return redirect()->route('backups.index')
        ->with('success','Backup Created successfully.');


    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Backup $backup)
    {
        return view('backups.show',compact('backups'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Backup  $backup
     * @return \Illuminate\Http\Response
     */
    public function edit(Backup $backup)
    {
        return view('backup.edit',compact('backup'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Backup $backup)
    {
       /*  request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
       */

        $backup->update($request->all());


        return redirect()->route('backup.index')
                        ->with('success','Instances updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Backup $backup)
    {
        $backup->delete();


        return redirect()->route('backup.index')
                        ->with('success','Instance deleted successfully');
    }
}