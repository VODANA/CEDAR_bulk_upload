<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use App\Models\Setting;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct('template');
    }

    public function index()
    {
        $data['templates'] = Template::latest()->get();
        return view('template.index', $data);
    }

    public function create()
    {
        
        return view('template.create');
    }

    public function store(TemplateRequest $request)
    {
      //  dd($request->file_path->getClientOriginalName());
        $request->validate(['file_path' => 'required|mimes:csv,txt,xlx,xls,pdf,json|max:2048']);
        $template = new Template;

        try{
            $fileName = time().'_'.$request->file_path->getClientOriginalName();
            $filePath = $request->file('file_path')->storeAs('uploads', $fileName, 'public'); 
            $template->file_path = 'storage/app/public/'. $filePath;
            $template->name = $request->name;
            $template->description = $request->description;
            $template->folder_id = $request->folder_id;
            $template->save();

            $notification = array(
                'message' => 'Template saved successfully!',
                'alert-type' => 'success'
            );

            $setting = new Setting;
            $setting=$setting->getSettings(auth()->id());

            if(!$setting)
                return view('setting.create');

            $secureurl ="https://resource.".$setting->url."/templates?folder_id=https%3A%2F%2Frepo.".$setting->url."%2Ffolders%2F".$template->folder_id; //Folder Id
            $templateJson = file_get_contents(base_path($template->file_path));
            $templateArray = json_decode($templateJson, true);
            
         //   dd($template->file_path); //[0]['uri']);

           //dd($templateArray);
           // dd($te$templateArray['properties'][$templateArray['_ui']['order'][9]]['_valueConstraints']['literals']mplateArray['_ui']['order']); // list of ui elements variables
            //dd($templateArray['properties']['Health Facility name']['_valueConstraints']['branches'][0]['uri']);
            //dd($templateArray['properties']['Live birth']['_ui']['inputType']);
            //dd($templateArray['properties']['Live birth']['_valueConstraints']['literals']);
           
            unset($templateArray["@id"]);
            $template->createTemplate($secureurl,$setting->api_token,json_encode($templateArray));
         
         //   $data['templates']=$templateArray['properties']['Live birth']['_valueConstraints']['literals'];
          //  $data['templates']=$templateArray; //[$templateArray['_ui']['order'][9]]['_valueConstraints']['literals']; //['properties'][$templateArray['_ui']['order'][9]]['_valueConstraints']['literals'];
          //  return view('template.index', $data);

           return redirect()->route('templates.index')->with($notification);

        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->route('templates.index')->with($notification);
        }
    }

    public function show(Template $template)
    {
        //
    }

    public function edit(Template $template)
    {
        $data['template'] = $template;
        return view('template.edit', $data);
    }

    public function update(TemplateRequest $request, Template $template)
    {
        try {
            $template = $template->update($request->all());

            $notification = array(
                'message' => 'Template saved successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('templates.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('templates.index')->with($notification);
        }
    }

    public function destroy(Template $template)
    {
        try{
            Template::find($template->id)->delete();

            $notification = array(
                'message' => 'Template deleted successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('templates.index')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('templates.index')->with($notification);
        }
    }
}
