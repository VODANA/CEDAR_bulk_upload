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
        $sync_to_dhis = new DHISSync;
        $anc=$sync_to_dhis->getANCMonthlyReport();
        //  dd($ga_grater_17);


        $fileName = time().'_'.$request->file_path->getClientOriginalName();
        $filePath = $request->file('file_path')->storeAs('uploads', $fileName, 'public');
        $sync_to_dhis->file_path = 'storage/app/public/'. $filePath;
        $sync_to_dhis->period= $request->name;
     
        //$setting = new Setting;
     //   $setting=$setting->getSettings(auth()->id());
        $dhis_report_template = file_get_contents(base_path($sync_to_dhis->file_path));
   

        $dhis='{
            "dataValues": [
                {
                    "dataElement": "cmav5iXqALq",
                    "categoryOptionCombo": "Ag4OgKZMVDG",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "12"
                },
                {
                    "dataElement": "cmav5iXqALq",
                    "categoryOptionCombo": "QsPXdfSZPX0",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "10"
                },
                {
                    "dataElement": "lotDArt3TsU",
                    "categoryOptionCombo": "Y6mCxOaXf15 ",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "20"
                },
                {
                    "dataElement": "lotDArt3TsU",
                    "categoryOptionCombo": "XoVZdRcCyu8",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "21"
                },
                {
                    "dataElement": "lotDArt3TsU",
                    "categoryOptionCombo": "OsOMSbkWYIN",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "22"
                },
                {
                    "dataElement": "dCyyyAiuofb",
                    "categoryOptionCombo": "Y6mCxOaXf15 ",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "30"
                },
                {
                    "dataElement": "dCyyyAiuofb",
                    "categoryOptionCombo": "XoVZdRcCyu8",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "31"
                },
                {
                    "dataElement": "dCyyyAiuofb",
                    "categoryOptionCombo": "OsOMSbkWYIN",
                    "period": "201403",
                    "orgUnit": "eRYEIdx853B",
                    "value": "32"
                }
        
            ]
        }';
        //$synctoallegros =SyncToAllegro::where('Age',['@value'=>41])->get();;


        //$synctoallegros = SyncToAllegro::where('name', 'sam')
        //->get();
        $dhis_data_elements=json_decode($dhis_report_template);
        //dd($test->dataValues[0]->value);//->dataValues->dataElement);
       // dd($dhis_data_elements);


        $result = $sync_to_dhis->syncDHIS($dhis_data_elements,$anc);
        if(!$result) {
            foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
                $dataelement->value=$anc[$key]->count();
                $dataelement->period=date("Ym");
            }
            $dhis2_content=json_encode($dhis_data_elements);

          //  $dhis2=fopen('dhis2.json','c',$dhis2_data);
            $dhis2_json_report="dhis2.json";
            $dhis_json = fopen($dhis2_json_report, "w") or die("Unable to open file!");
            fwrite($dhis_json, $dhis2_content);
            fclose($dhis_json);
            // dd($dhis2);
            /* dd($dhis_data_elements);
            $dhis2_data=json_encode($dhis_data_elements);
            $dhis_data_elements="touch $dhis2_data .";
            exec($dhis_data_elements);*/
        return response()->download(public_path($dhis2_json_report));
        }
  
    return view('synctoallegro.index',compact('synctoallegros'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
