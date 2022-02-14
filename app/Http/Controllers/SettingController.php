<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;

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

            $setting = Setting::create($request->all());
            $setting->user_id=auth()->id();
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
            $setting = $setting->update($request->all());

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
