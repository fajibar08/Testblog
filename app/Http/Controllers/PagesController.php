<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Reply;
use App\Comment;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        if(auth()->check()){

        $data = array(
                'profile'   => User::find(auth()->user()->id),
                'replies'   =>Reply::orderBy('created_at', 'asc')->get(),
                'comments'  =>Comment::orderBy('created_at', 'asc')->get(),
                'posts'     => Post::orderBy('created_at', 'desc')->get()
                );
        
        return redirect('home')->with($data);
        }

        return view('auth.login');
    }
}
