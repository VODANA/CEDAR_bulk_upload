<?php


namespace App\Http\Controllers;


use App\Models\SyncToAllegro;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Template;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class SyncCEDARToAllegroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      /*  $synctoallegros = SyncToAllegro::all();
     //   dd($synctoallegros);
        $setting = new Setting;
        $setting=$setting->getSettings(auth()->id());
        $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2Fallegrosyncfolderid"; //Folder Id
        //Read template instance
        // $templateJson = file_get_contents(base_path($allegrosync->instance_path));
        // $templateJson=$synctoallegros[0];
       // $templates =  new Template;
       // $templateJson=$templates->getTemplate("Anten");
        $b = new SyncToAllegro;
        $templateJson=$b->getTemplateInstances("CO");
       // dd($templateJson);
        $b->bulkSync($templateJson);

       for($k=0; $k < count($templateJson); $k++) {
            $b->bulkSync($templateJson[$k]);
        }
      //  dd($stats);
        //return redirect()->route('allegrosyncs.index')->with($notification);

        //$users = Book::where('Sex','Male')->get();
        //dd($instances);
        //$books=json_decode($books);
*/
        return view('synccedartoallegro.index',compact('synccedartoallegros'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       /* $p="storage/app/public/uploads/AyderANC.json";
        //  dd($request->all());
          // $p="storage/app/public/uploads/1646503572_lumc.json";
        //$p="storage/app/public/uploads/1644926143_CovidTemplate.json";
      */  
      //  $templates =  new Template;
        $templateJson=Template::all(); //->getTemplate("Anten");
        $templateArray = json_decode($templateJson, true);
       // foreach
        dd($templateArray[9]['title']);
      /*  $dataElementsJson = file_get_contents(base_path($p));
        $templateArray = json_decode($templateJson, true);
        $dataElementsArray = json_decode($dataElementsJson, true);
       // dd($dataElementsArray);
        $dataElements = $templates->getDataElements($dataElementsJson);
       // dd($dataElements);

      //  $s->createBackup($backup_dir,$database);
      // $templateArray[1]=$templateJson2;
      // $templateArray[2]=$templateArray['properties'];
      
    //   dd($templateArray);
        $data = array('templates'=>$templateArray[0] , 'dataElements'=>$dataElements); //[$templateArray['_ui']['order'][9]]['_valueConstraints']['literals']; //['properties'][$templateArray['_ui']['order'][9]]['_valueConstraints']['literals'];
       
    return view('synctoallegro.create', $data);
        return view('synctoallegro.create');
    } */
    return view('synccedartoallegro.create');
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
        $instance_path="storage/app/public/uploads/CovidInstance2.json";
        $templateJson = file_get_contents(base_path($instance_path));
        $s->rdf=$templateJson;
        $s->save();
       // SyncToAllegro::create( $request->rdf);

     //   $s->postToAllegro($secureurl , $apiKey , $request->rdf);
       // $s->createJsonLDInstance($request->all() , $secureurl , $apiKey , $vocabularyUrl='', $templateJson);

        return redirect()->route('synccedartoallegros.index')
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
        return view('synccedartoallegro.show',compact('synccedartoallegro'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SyncToAllegro  $synctoallegro
     * @return \Illuminate\Http\Response
     */
    public function edit(SyncToAllegro $synctoallegro)
    {
        return view('synccedartoallegro.edit',compact('synccedartoallegro'));
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