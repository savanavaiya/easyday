<?php

namespace App\Http\Controllers;

use App\Models\Participate;
use App\Models\Project;
use App\Models\ProjectAttr;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        // return response()->json(['message'=>'ok']);

        $validate = $request->validate([
            'project_name' => 'required',
            'description' => 'required',
            'assign_color' => 'required',
            'participates' => 'required|array',
        ]);

        $data = Project::create([
            'user_id' => auth('sanctum')->user()->id,
            'project_name' => $request->project_name,
            'description' => $request->description,
            'assign_color' => $request->assign_color,
        ]);

        $prid = Project::orderBy('created_at','DESC')->first();

        $cnt = count($request->participates);

        for($i=0;$i<$cnt;$i++){
            $data = Participate::create([
                'project_id' => $prid->id,
                'participate_name' => $request->participates[$i]['name'],
                'role' => $request->participates[$i]['role'],
                'contact_number' => $request->participates[$i]['contact_number'],
                'image' => $request->participates[$i]['image'],
            ]);
        }

        return response()->json(['success'=>'true','message'=>'Create Project Successfully'],200);
    }

    public function getusepro()
    {
        $data = Project::where('user_id',auth('sanctum')->user()->id)->get();

        if($data->isEmpty()){
            return response()->json(['success'=>'false','message'=>'No Data Found'],200);
        }else{
            return response()->json(['success'=>'true','message'=>$data],200);
        }
    }

    public function getpro()
    {
        // return response()->json(['message'=>'ok']);

        $data = Project::where('user_id',auth('sanctum')->user()->id)->where('id',Request()->project_id)->first();

        if($data == null){
            return response()->json(['success'=>'false','message'=>'No Data Found'],200);
        }else{
            return response()->json(['success'=>'true','message'=>$data],200);
        }
    }

    public function addattr(Request $request)
    {

        $validate = $request->validate([
            'attribute_type' => 'required',
            'attribute_name' => 'required',
            'project_id' => 'required',
        ]);

        $data = ProjectAttr::create([
            'attribute_type' => $request->attribute_type,
            'attribute_name' => $request->attribute_name,
            'project_id' => $request->project_id,
        ]);

        return response()->json(['success'=>'true','message'=>'Successfully Created'],200);
    }

    public function getattr()
    {
        // return response()->json(['message'=>'ok']);

        $type = Request()->type;
        $project_id = Request()->project_id;

        $data = ProjectAttr::where('attribute_type',$type)->where('project_id',$project_id)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
    }

    public function delproject()
    {
        // return response()->json(['message'=>Request()->project_id]);

        $project_id = Request()->project_id;

        $data = Project::find($project_id);

        $data->delete();

        return response()->json(['success'=>'true','message'=>'Delete Project Successfully'],200);
    }

    public function ediproject(Request $request)
    {
        // return response()->json(['success'=>'true','message'=>$request->all()],200);
        
        $project_id = Request()->project_id;

        if($project_id == null){
            return response()->json(['success'=>'false','message'=>'Please Enter Project id'],403);
        }

        $validate = $request->validate([
            'project_name' => 'required',
            'description' => 'required',
            'assign_color' => 'required',
        ]);

        $data = Project::find($project_id);

        $data->project_name = $request->project_name;
        $data->description = $request->description;
        $data->assign_color = $request->assign_color;
        $data->save();

        return response()->json(['success'=>'true','message'=>'Update Project Successfully'],200);


    }

    // public function addpart(Request $request)
    // {

    //     $validate = $request->validate([
    //         'project_id' => 'required',
    //         'participates' => 'required|array',
    //     ]);

    //     $cnt = count($request->participates);

    //     for($i=0;$i<$cnt;$i++){
    //         $data = Participate::create([
    //             'project_id' => $request->project_id,
    //             'participate_name' => $request->participates[$i]['name'],
    //             'role' => $request->participates[$i]['role'],
    //             'contact_number' => $request->participates[$i]['contact_number'],
    //             'image' => $request->participates[$i]['image'],
    //         ]);
    //     }

    //     return response()->json(['success'=>'true','message'=>'Add Participate Successfully'],200);

    // }

    public function gepart()
    {
        $project_id = Request()->project_id;

        $data = Participate::where('project_id',$project_id)->get();

        return response()->json(['success'=>'true','message'=>$data],200);

    }
}
