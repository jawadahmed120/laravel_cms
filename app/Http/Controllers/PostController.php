<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function delete(Post $post){
        if(auth()->user()->cannot("delete",$post)){
            return "You can not do this";
        }
        $post->delete();

        return redirect("/profile/" . auth()->user()->username )->with("success","Post deleted Successfully");
    }
    
    public function viewSinglePost(Post $post){
        return view("single_post",["post" => $post]);
    }

    public function storeNewPost(Request $request){
        $incomingFields = $request->validate([
            "title" => "required",
            "body" => "required"
        ]);
        $incomingFields["title"] = strip_tags($incomingFields["title"]);
        $incomingFields["body"] = strip_tags($incomingFields["body"]);
        $incomingFields["user_id"] = auth()->id();

        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with("Success","Post created Successfully");
    }

    public function showCreateForm(){
        return view("create-post");
    }
}
