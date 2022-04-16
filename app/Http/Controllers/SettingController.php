<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


use Exception;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct('setting');
    }

    public function index()
    {
        $data['settings'] = Setting::latest()->get();
        return view('setting.index', $data);
    }

    public function create()
    {
        return view('setting.create');
    }

    public function store(SettingRequest $request)
    {
        try{
            $setting = new Setting;
            $setting->site_name=$request->site_name;
            $setting->url=$request->url;
            $setting->api_token=$request->api_token;
            $setting->folder_id=$request->folder_id;
            $setting->location=$request->location;
            $setting->user_id=auth()->id();
           
            // HMIS Setting
            $setting->hmis_username=$request->hmis_username;
            $setting->hmis_password=base64_encode($request->hmis_password);
            $setting->hmis_url=$request->hmis_url;
          //  dd($setting->hmis_password);
            // HMIS Setting
            $setting->allegro_username=$request->allegro_username;
            $setting->allegro_password=base64_encode($request->allegro_password);
            $setting->allegro_url=$request->allegro_url;

            // HMIS Setting
            $setting->backup_path=$request->backup_path;
            
            $setting->save();   

            $notification = array(
                'message' => 'Setting saved successfully!',
                'alert-type' => 'success'
            );  

            return redirect()->route('settings.index')->with($notification);

        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->route('settings.index')->with($notification);
        }
    }

    public function show(Setting $setting)
    {
        //
    }

    public function edit(Setting $setting)
    {
        $data['setting'] = $setting;
        return view('setting.edit', $data);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        try {
         //   dd($request->all());
            $setting = $setting->update($request->all());
          //  $setting->hmis_password=base64_encode($request->hmis_password);
            //$setting->allegro_password=base64_encode($request->allegro_password);
          //  $setting->save();   
            $notification = array(
                'message' => 'Setting saved successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('settings.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('settings.index')->with($notification);
        }
    }

    public function destroy(Setting $setting)
    {
        try{
            Setting::find($setting->id)->delete();

            $notification = array(
                'message' => 'Setting deleted successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('settings.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('settings.index')->with($notification);
        }
    }
}
