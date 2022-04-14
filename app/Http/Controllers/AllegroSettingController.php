<?php


namespace App\Http\Controllers;


use App\Models\SyncToAllegro;
use Illuminate\Http\Request;
use App\Models\Setting;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class AllegroSettingController extends Controller
{
    public function __construct()
    {
        parent::__construct('allegrosetting');
    }

    public function index()
    {
        $data['allegrosettings'] = AllegroSetting::latest()->get();
        return view('allegrosetting.index', $data);
    }

    public function create()
    {
        return view('allegrosetting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'rdf' => 'required',
        ]);

        $s = new SyncToAllegro;
        $setting = new Setting;
        $setting=$setting->getSettings(auth()->id());
        $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2Fallegrosyncfolderid"; //Folder Id
        $apiKey = $setting->api_key;

        $s->postToAllegro($secureurl , $apiKey , $request->rdf);

        return redirect()->route('allegrosetting.index')
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
        return view('allegrosetting.show',compact('allegrosetting'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SyncToAllegro  $synctoallegro
     * @return \Illuminate\Http\Response
     */
    public function edit(SyncToAllegro $synctoallegro)
    {
        return view('allegrosetting.edit',compact('allegrosetting'));
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


        return redirect()->route('allegrosetting.index')
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


        return redirect()->route('allegrosetting.index')
                        ->with('success','Instance deleted successfully');
    }
}