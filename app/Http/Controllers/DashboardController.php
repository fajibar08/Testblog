<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Reply;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile        = User::find(auth()->user()->id);
        $replies        = Reply::orderBy('created_at', 'asc')->get();
        $comments       = Comment::orderBy('created_at', 'asc')->get();
        $posts          = Post::orderBy('created_at', 'desc')->get();
        return view('pages.dashboard', compact('posts', 'user_id', 'comments', 'replies', 'profile'));
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
        $this->validate($request, [
            'post_image'    => 'image|nullable|max:50000'
        ]);

        if(!empty($request->input('post_content')) || $request->hasFile('post_image')){
            if($request->hasFile('post_image')){
                $fileNameWithExt    = $request->file('post_image')->getClientOriginalName();
                $fileName           = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file('post_image')->getClientOriginalExtension();
                $fileNameToStore    = $fileName . '_' . time() . '.' . $extension;
                $path               = $request->file('post_image')->storeAs('public/post_images', $fileNameToStore);
            }else{
                $fileNameToStore    = NULL;
            }

            $posts                  = new Post;
            $posts->post_content    = $request->input('post_content');
            $posts->user_id         = auth()->user()->id;
            $posts->post_image      = $fileNameToStore;
            $posts->post_type       = '1';
            $posts->save();

            $data = array(
                'profile'         => User::find(auth()->user()->id),
                'replies'         => Reply::orderBy('created_at', 'asc')->get(),
                'posts'           => Post::orderBy('created_at', 'desc')->get(),
                'comments'        => Comment::orderBy('created_at', 'asc')->get(),
                'alert_message'   => 'Post created.'
                );
            
            return redirect()->back()->with($data);
        }else{
            return back()->withErrors([
                'post_content'  => "What's on your mind?"
            ]);
        }
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validate($request, [
            'post_image'    => 'image|nullable|max:5000',
            
        ]);

        if($request->hasFile('post_image')){
            $fileNameWithExt    = $request->file('post_image')->getClientOriginalName();
            $fileName           = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('post_image')->getClientOriginalExtension();
            $fileNameToStore    = $fileName . '_' . time() . '.' . $extension;
            $path               = $request->file('post_image')->storeAs('public/post_images', $fileNameToStore);
        }

        $posts                  = Post::find($id);
        $posts->post_content    = $request->input('post_content');
        if($request->hasFile('post_image')){
            Storage::delete('public/post_images/'.$posts->post_image);
            $posts->post_image  = $fileNameToStore;      
        }
        $posts->save();

        $data = array(
            'profile'         => User::find(auth()->user()->id),
            'replies'         => Reply::orderBy('created_at', 'asc')->get(),
            'posts'           => Post::orderBy('created_at', 'desc')->get(),
            'comments'        => Comment::orderBy('created_at', 'asc')->get(),
            'alert_message'   => 'Post Updated.'
            );
        
        return redirect('dashboard')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        Storage::delete('public/post_images/'.$post->post_image);
    
        $post->delete();

        $comment = Comment::where('post_id', $id);
        $comment->delete();
        
        $reply = Reply::where('post_id', $id);
        $reply->delete();

        $data = array(
                'profile'         => User::find(auth()->user()->id),
                'replies'         => Reply::orderBy('created_at', 'asc')->get(),
                'posts'           => Post::orderBy('created_at', 'desc')->get(),
                'comments'        => Comment::orderBy('created_at', 'asc')->get(),
                'alert_message'   => 'Post Deleted.'
                );
        
        return redirect('dashboard')->with($data);
    }
}
