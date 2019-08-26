<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Reply;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeleteCommentsController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_comment($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        
        $reply = Reply::where('comment_id', $id);
        $reply->delete();

        $data = array(
            'profile'         => User::find(auth()->user()->id),
            'replies'         => Reply::orderBy('created_at', 'asc')->get(),
            'posts'           => Post::orderBy('created_at', 'desc')->get(),
            'comments'        => Comment::orderBy('created_at', 'asc')->get()
            );
        
        return redirect()->back()->with($data);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_reply($id)
    {
        $comment = Reply::find($id);
        $comment->delete();
        
        $data = array(
            'profile'         => User::find(auth()->user()->id),
            'replies'         => Reply::orderBy('created_at', 'asc')->get(),
            'posts'           => Post::orderBy('created_at', 'desc')->get(),
            'comments'        => Comment::orderBy('created_at', 'asc')->get()
            );
        
        return redirect()->back()->with($data);
        
    }
}
