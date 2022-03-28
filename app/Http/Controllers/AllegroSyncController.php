<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AllegroSyncRequest;
use App\Models\AllegroSync;
use App\Models\Setting;


class AllegroSyncController extends Controller
{
    public function __construct()
    {
        parent::__construct('allegrosync');
    }

    public function index()
    {
        $data['allegrosyncs'] = AllegroSync::latest()->get();
        return view('allegrosync.index', $data);
    }

    public function create()
    {
        return view('allegrosync.create');
    }
    public function sync()
    {
        return view('allegrosync.sync');
    }

    public function store(AllegroSyncRequest $request)
    {
        $request->validate(['file_path' => 'required|mimes:csv,txt,xlx,xls,pdf,json|max:2048']);

        $allegrosync = new AllegroSync;

        try{
            $allegrosync->name = $request->name;
            $fileName = time().'_'.$request->file_path->getClientOriginalName();
            $tmpl_instance = time().'_'.$request->instance_path->getClientOriginalName();
            $filePath = $request->file('file_path')->storeAs('uploads', $fileName, 'public');
            $tmpl_instance_path = $request->file('instance_path')->storeAs ('uploads', $tmpl_instance, 'public');
            $allegrosync->file_path = 'storage/app/public/'. $filePath;
            $allegrosync->instance_path = 'storage/app/public/'. $tmpl_instance_path;
            $allegrosync->vocabulary_url= $request->vocabulary_url;
            $allegrosync->folder_id= $request->folder_id;
            $allegrosync->save();
            $notification = array(
                'message' => 'Bulk data synced successfully!',
                'alert-type' => 'success'
            );
         
            $setting = new Setting;
            $setting=$setting->getSettings(auth()->id());
            $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2F".$allegrosync->folder_id; //Folder Id
            //Read template instance
            $templateJson = file_get_contents(base_path($allegrosync->instance_path));
            $allegrosync->bulkSync($allegrosync->file_path , $secureurl , $setting->api_token ,$allegrosync->vocabulary_url, $templateJson);
            return redirect()->route('allegrosyncs.index')->with($notification);

        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->route('allegrosyncs.index')->with($notification);
        }
    }

    public function show(AllegroSync $Allegrosync)
    {
        //
    }

    public function edit(AllegroSync $allegrosync)
    {
        $data['allegrosync'] = $allegrosync;
        return view('allegrosync.edit', $data);
    }

    public function update(AllegroSyncRequest $request, AllegroSync $allegrosync)
    {
        try {
            $allegrosync = $allegrosync->update($request->all());

            $notification = array(
                'message' => 'Bulk data synced successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('allegrosyncs.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('allegrosyncs.index')->with($notification);
        }
    }

    public function destroy(AllegroSync $allegrosync)
    {
        try{
            $allegrosync::find($allegrosync->id)->delete();

            $notification = array(
                'message' => 'Deleted successful',
                'alert-type' => 'success'
            );

            return redirect()->route('allegrosyncs.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('allegrosyncs.index')->with($notification);
        }
    }
}
