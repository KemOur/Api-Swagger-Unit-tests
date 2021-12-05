<?php

namespace App\Http\Controllers;

//use App\Models\User;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{

    public function tasksByUser(Request $request)
    {
        $tasks = Tasks::where('user_id', );

        return response()->json([
            "title" => $tasks->title,
            "body" => $tasks->body,
            //"tasks"=>$tasks

        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $newPost = new Tasks;
        $newPost->user_id = 1;
        $newPost->title = $request->title;
        $newPost->body = $request->body;
        $newPost->save();

        return $newPost;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $post = Tasks::find($id);

        if (!$post) {
            return response()->json(['message' => "Not Found"], 404);
        }

        if ($post->user_id != $request->user()->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
