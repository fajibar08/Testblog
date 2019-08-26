<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Reply;
use App\Comment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $profile    = User::find(auth()->user()->id);
        $replies    = Reply::orderBy('created_at', 'asc')->get();
        $comments   = Comment::orderBy('created_at', 'asc')->get();
        $posts      = Post::orderBy('created_at', 'desc')->get();

        return view('pages.home', compact('posts', 'comments','replies', 'profile'));
    }

    public function store_comments(Request $request){
        $comments = new Comment;
        $comments->comments = $request->input('comments');
        $comments->post_id  = $request->input('post_id');
        $comments->user_id  = auth()->user()->id;
        $comments->save();

        $data = array(
                'profile'   => User::find(auth()->user()->id),
                'replies'   => Reply::orderBy('created_at', 'asc')->get(),
                'comments'  => Comment::orderBy('created_at', 'asc')->get(),
                'posts'     => Post::orderBy('created_at', 'desc')->get()
                );
        
        return redirect()->back()->with($data);
    }

    public function store_reply(Request $request){
        $comments               = new Reply;
        $comments->replies      = $request->input('reply');
        $comments->comment_id   = $request->input('comment_id');
        $comments->post_id      = $request->input('post_id');
        $comments->user_id      = auth()->user()->id;
        $comments->save();

        $data = array(
                'profile'   => User::find(auth()->user()->id),
                'replies'   => Reply::orderBy('created_at', 'asc')->get(),
                'comments'  => Comment::orderBy('created_at', 'asc')->get(),
                'posts'     => Post::orderBy('created_at', 'desc')->get()
                );
        
        return redirect()->back()->with($data);
    }
}
