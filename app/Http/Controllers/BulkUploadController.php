<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BulkUploadRequest;
use App\Models\BulkUpload;
use App\Models\Setting;


class BulkUploadController extends Controller
{
    public function __construct()
    {
        parent::__construct('bulkupload');
    }

    public function index()
    {
        $data['bulkuploads'] = Bulkupload::latest()->get();
        return view('bulkupload.index', $data);
    }

    public function create()
    {
        return view('bulkupload.create');
    }

    public function store(BulkUploadRequest $request)
    {
        $request->validate(['file_path' => 'required|mimes:csv,txt,xlx,xls,pdf,json|max:2048']);

        $bulkInput = new BulkUpload;

        try{
            $bulkInput->name = $request->name;
            $fileName = time().'_'.$request->file_path->getClientOriginalName();
            $tmpl_instance = time().'_'.$request->instance_path->getClientOriginalName();
            $filePath = $request->file('file_path')->storeAs('uploads', $fileName, 'public');
            $tmpl_instance_path = $request->file('instance_path')->storeAs ('uploads', $tmpl_instance, 'public');
            $bulkInput->file_path = 'storage/app/public/'. $filePath;
            $bulkInput->instance_path = 'storage/app/public/'. $tmpl_instance_path;
            $bulkInput->vocabulary_url= $request->vocabulary_url;
            $bulkInput->folder_id= $request->folder_id;
            $bulkInput->save();

            $notification = array(
                'message' => 'Bulk data uploaded successfully!',
                'alert-type' => 'success'
            );
         
            $setting = new Setting;
            $setting=$setting->getSettings(auth()->id());
            $secureurl ="https://resource.".$setting->url."/template-instances?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2F".$bulkInput->folder_id; //Folder Id
            //Read template instance
            $templateJson = file_get_contents(base_path($bulkInput->instance_path));

            $bulkInput->bulkUpload($bulkInput->file_path , $secureurl , $setting->api_token , $templateJson);
            return redirect()->route('bulkuploads.index')->with($notification);

        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->route('bulkuploads.index')->with($notification);
        }
    }

    public function show(BulkUpload $bulkupload)
    {
        //
    }

    public function edit(BulkUpload $bulkupload)
    {
        $data['bulkupload'] = $bulkupload;
        return view('bulkupload.edit', $data);
    }

    public function update(BulkUploadRequest $request, BulkUpload $bulkupload)
    {
        try {
            $bulkupload = $bulkupload->update($request->all());

            $notification = array(
                'message' => 'Bulk data uploaded successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('bulkuploads.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('bulkuploads.index')->with($notification);
        }
    }

    public function destroy(BulkUpload $bulkupload)
    {
        try{
            bulkupload::find($bulkupload->id)->delete();

            $notification = array(
                'message' => 'Deleted successful',
                'alert-type' => 'success'
            );

            return redirect()->route('bulkuploads.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('bulkuploads.index')->with($notification);
        }
    }
}
