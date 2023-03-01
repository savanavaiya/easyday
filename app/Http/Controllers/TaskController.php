<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Likecomment;
use App\Models\Task;
use App\Models\TaskParticipate;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {

        $validate = $request->validate([
            'task_title' => 'required',
            'priority' => 'required',
            'tags' => 'required',
            'space' => 'required',
            'red_flag' => 'required|boolean',
            'zone' => 'required',
            'due_date' => 'required',
            'task_participate' => 'required|array',
        ]);

        $project_id = Request()->project_id;

        // return response()->json(['success'=>'false','message'=>$request->tags],403);

        if($project_id == null){
            return response()->json(['success'=>'false','message'=>'Please Enter Project Id'],403);
        }

        $data = Task::create([
            'project_id' => $project_id,
            'task_title' => $request->task_title,
            'priority' => $request->priority,
            'tags' =>  implode(',',$request->tags),
            'space' => $request->space,
            'red_flag' => $request->red_flag,
            'zone' => $request->zone,
            'due_date' => $request->due_date,
        ]);

        $taskid = Task::where('project_id',$project_id)->orderBy('created_at','DESC')->first();

        $cnt = count($request->task_participate);

        for($i=0;$i<$cnt;$i++){
            $data2 = TaskParticipate::create([
                'project_id' => $project_id,
                'task_id' => $taskid->id,
                'task_participate' => $request->task_participate[$i],
            ]);
        }

        return response()->json(['success'=>'true','message'=>'Add Task Successfully'],200);
    }

    public function gettask()
    {
        $project_id = Request()->project_id;

        $data = Task::with('taskpart','taskpart.taskpartname')->where('project_id',$project_id)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
        
    }

    public function addcomm(Request $request)
    {
        $validate = $request->validate([
            'comment' => 'required',
            'task_id' => 'required',
        ]);

        if($request->parentcomment_id == null){
            $par_id = '0';
        }else{
            $par_id = $request->parentcomment_id;
        }

        $user_id = auth('sanctum')->user()->id;

        if($request->file('comment')){
            $new = 'Aud'.time().'MP3';

            $request->comment->move(public_path('audio'),$new);

            $com = public_path().'\\audio\\'.$new;

        }else{
            $com = $request->comment;
        }

        $data = Comment::create([
            'comment' => $com,
            'parentcomment_id' => $par_id,
            'user_id' => $user_id,
            'task_id' => $request->task_id,
        ]);

        return response()->json(['success'=>'true','message'=>'Add Comments Successfully'],200);
    }

    public function getcomm()
    {
        $task_id = Request()->task_id;

        $data = Comment::where('task_id',$task_id)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
    }

    public function likecom()
    {
        // return response()->json(['message'=>'ok'],200);
        $comment_id = Request()->comment_id;

        $user_id = auth('sanctum')->user()->id;

        $data = Likecomment::create([
            'likeruser' => $user_id,
            'comment_id' => $comment_id,
        ]);

        return response()->json(['success'=>'true','message'=>'Add Like Successfully'],200);
    }

    public function likecomrec()
    {
        $comment_id = Request()->comment_id;

        $data = Likecomment::where('comment_id',$comment_id)->get();

        return response()->json(['success'=>'true','message'=>$data],200);
    }

    public function likecomreturn()
    {
        $comment_id = Request()->comment_id;

        $user_id = auth('sanctum')->user()->id;

        $data = Likecomment::where('comment_id',$comment_id)->where('likeruser',$user_id)->first();

        $data->delete();

        return response()->json(['success'=>'true','message'=>'Like Return Successfully'],200);
    }
}
