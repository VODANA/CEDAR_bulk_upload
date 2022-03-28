<?php


namespace App\Http\Controllers;


use App\Models\SyncToAllegro;
use Illuminate\Http\Request;
use App\Models\Setting;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class SyncToAllegroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $synctoallegros = SyncToAllegro::all();
        // $synctoallegros = SyncToAllegro::all();
        //$synctoallegros = SyncToAllegro::where('Age.value', '14')
        $anc[0] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$lt' => 16]])->get();
        $anc[1] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$gt' => 15]])->get();
        $anc[2] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 9, '$lt' => 15]])->get();
        $anc[3] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 14, '$lt' => 20]])->get();
        $anc[4] = SyncToAllegro::whereRaw(['Age.@value' => ['$gt' => 19]])->get();
        $anc[5] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$gt' => 15]])->get();
        $anc[6] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$gt' => 15]])->where('Sex.@value', 'Male')->get();
        $anc[7] = SyncToAllegro::whereRaw(['GestetionalAge.@value' => ['$lt' => 15]])->where('Sex.@value', 'Male')->get();
      //  dd($ga_grater_17);
     
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
        $dhis_data_elements=json_decode($dhis);
        //dd($test->dataValues[0]->value);//->dataValues->dataElement);
        foreach($dhis_data_elements->dataValues as $key=>$dataelement) {
            $dataelement->value=$anc[$key]->count();
            $dataelement->period=date("Ym");
        }
        $dhis2_data=json_encode($dhis_data_elements);

        $dhis2=fopen('dhis2.json','c',$dhis2_data);
        $myfile = fopen("dhis2.json", "w") or die("Unable to open file!");
        $txt = $dhis2_data;
        fwrite($myfile, $txt);
        fwrite($myfile, $txt);
        fclose($myfile);
       // dd($dhis2);
       /* dd($dhis_data_elements);
        $dhis2_data=json_encode($dhis_data_elements);
        $dhis_data_elements="touch $dhis2_data .";
        exec($dhis_data_elements);*/
        return response()->download(public_path('dhis2.json'));

        //  dd($synctoallegros);
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
        return view('synctoallegro.index',compact('synctoallegros'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $p="storage/app/public/uploads/1646418577_uganda.json";
        //  dd($request->all());
          // $p="storage/app/public/uploads/1646503572_lumc.json";
        //$p="storage/app/public/uploads/1644926143_CovidTemplate.json";
       
        $templateJson = file_get_contents(base_path($p));
        $templateArray = json_decode($templateJson, true);
     //   dd($database);
      //  $s->createBackup($backup_dir,$database);
       // dd($templateArray);
        $data['templates']=$templateArray; //[$templateArray['_ui']['order'][9]]['_valueConstraints']['literals']; //['properties'][$templateArray['_ui']['order'][9]]['_valueConstraints']['literals'];

    return view('synctoallegro.create', $data);
       // return view('synctoallegro.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // request()->validate([
         //   'rdf' => 'required',
        //]);
    //    dd($request->all());

        $s = new SyncToAllegro;
        $setting = new Setting;
        $setting=$setting->getSettings(auth()->id());
        $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2Fallegrosyncfolderid"; //Folder Id
        $apiKey = $setting->api_key;
    //    $s->save($request->all());
     //   $s->postToAllegro($secureurl , $apiKey , $request->rdf);
        $instance_path="storage/app/public/uploads/1646548548_lumc.json";
        $templateJson = file_get_contents(base_path($instance_path));
        $s->rdf=$templateJson;
        $s->save();
       // SyncToAllegro::create( $request->rdf);

     //   $s->postToAllegro($secureurl , $apiKey , $request->rdf);
       // $s->createJsonLDInstance($request->all() , $secureurl , $apiKey , $vocabularyUrl='', $templateJson);

        return redirect()->route('synctoallegros.index')
                        ->with('success','Instamces synced to AllegroGraph successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(SyncToAllegro $synctoallegro)
    {
        return view('synctoallegro.show',compact('synctoallegro'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SyncToAllegro  $synctoallegro
     * @return \Illuminate\Http\Response
     */
    public function edit(SyncToAllegro $synctoallegro)
    {
        return view('synctoallegro.edit',compact('synctoallegro'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SyncToAllegro $synctoallegro)
    {
       /*  request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
       */

        $SyncToAllegro->update($request->all());


        return redirect()->route('SyncToAllegro.index')
                        ->with('success','Instances updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(SyncToAllegro $synctoallegro)
    {
        $synctoallegro->delete();


        return redirect()->route('synctoallegro.index')
                        ->with('success','Instance deleted successfully');
    }
}