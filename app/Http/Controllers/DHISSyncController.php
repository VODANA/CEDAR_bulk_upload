<?php


namespace App\Http\Controllers;


use App\Models\DHISSync;
use Illuminate\Http\Request;
use App\Models\Setting;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class DHISSyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $dhissyncs = DHISSync::all();
        return view('dhissync.index',compact('dhissyncs'))
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
       return view('dhissync.sync');
    }
      public function store(Request $request){
        $request->validate(['file_path' => 'required|mimes:csv,txt,xlx,xls,pdf,json|max:2048']);

        $setting = new Setting;
        $setting = $setting->getSettings(auth()->id());

        if(!$setting) {
              $notification = array(
                'message' => "HMIS Setting not yet set.Provide the necessary cridentials",
                'alert-type' => 'error'
              );
          return view('setting.create');
        }

        $sync_to_dhis = new DHISSync;
        $fileName = time().'_'.$request->file_path->getClientOriginalName();
        $filePath = $request->file('file_path')->storeAs('uploads', $fileName, 'public');
        $sync_to_dhis->file_path = 'storage/app/public/'. $filePath;
        $dhis_report_template = file_get_contents(base_path($sync_to_dhis->file_path));

        $sync_to_dhis->cleanAfterUse(base_path($sync_to_dhis->file_path));
        $dhis_data_elements=json_decode($dhis_report_template);

        $result = $sync_to_dhis->syncDHIS2($dhis_data_elements, $setting);
        if($result==-1) {
            $notification = array(
              'message' => "HMIS Setting not yet set.Provide the necessary cridentials",
              'alert-type' => 'error'
            );
          return view('setting.create')->with($notification);
        }

        if(!$result) {
            $dhis2_content=json_encode($dhis_data_elements);
            $dhis2_json_report="dhis2.json";

            $dhis_json = fopen($dhis2_json_report, "w") or die("Unable to open file!");
            fwrite($dhis_json, $dhis2_content);
            fclose($dhis_json);
        return response()->download(public_path($dhis2_json_report));
        }
  
    return view('dhissync.index',compact('sync_to_dhis'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
