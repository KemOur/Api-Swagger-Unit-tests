<?php

namespace App\Http\Controllers;

//use App\Models\User;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{

    public function tasksByUser(Request $request)
    {
        $tasks = Tasks::where('user_id',$request->user()->id )->orderBy('updated_at', 'desc')->get();

        //dd($tasks);
        return response()->json([
            "tasks"=>$tasks

        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $newTask = new Tasks;
        $newTask->user_id = $request->user()->id;
        $newTask->body = $request->body;
        $newTask->save();

        return response()->json([
            $newTask
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $task = Tasks::find($id);

        if (!$task) {
            return response()->json(['message' => "Not Found"], 404);
        }

        if ($task->user_id != $request->user()->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }

        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        //

        $task = Tasks::find($id);
        if (!$task) {
            return response()->json([
                "message" => "Cette tâche nhexiste pas"
            ], 404);
        }
        if ($request->user()->id != $task->user_id) {
            return response()->json([
                "message" => "Vous ne pouvez pas acceder à ce page!"
            ], 403);
        }
        $request->validate([
            'body' => 'required'
        ]);
        Tasks::where('id', $id)->update([
            'body' => $request->body
        ]);
        return response()->json([
            "success" => true
        ], 200);

        return response()->json(['message' => 'La tâche à été modifiée avec succés', 'task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json([
                "message" => "Tache innexistante"
            ], 404);
        }
        if ($request->user()->id != $task->user_id) {
            return response()->json([
                "message" => "Accès interdit!"
            ], 403);
        }
        Tasks::where('id', $id)->delete();
        return response()->json([
            "success" => true
        ], 200);
    }
}
