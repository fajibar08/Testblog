<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Reply;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $year = date('Y');
        $current_year = date('Y') - 25;
        $months = array(
                "January", "February", "March", 
                "April", "May", "June", 
                "July", "August", "September", 
                "October", "November", "December"
        );
        $profile = User::find($id);
        if($profile->birthday && $profile->address && $profile->status != NULL ){
            return view('pages.manage-profile', compact('year', 'current_year', 'months', 'profile'));
        }
        return view('pages.manage-profile', compact('year', 'current_year', 'months', 'profile'))
                ->withErrors([
                    'birthday'  => "You haven't set your birthday",
                    'address'   => "You haven't set your Address",
                    'status'    => "Select your relationship status"
                ]);
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
        if($request->hasFile('user_image')){
            $fileNameWithExt    = $request->file('user_image')->getClientOriginalName();
            $fileName           = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('user_image')->getClientOriginalExtension();
            $fileNameToStore    = $fileName . '_' . time() . '.' . $extension;
            $path               = $request->file('user_image')->storeAs('public/user_images', $fileNameToStore);
            $path               = $request->file('user_image')->storeAs('public/post_images', $fileNameToStore);
        }

        $profile                = User::find($id);
        $profile->name          = $request->input('name');
        $profile->email         = $request->input('email');
        $profile->birthday      = $request->input('birthday');
        $profile->address       = $request->input('address');
        $profile->status        = $request->input('status');
        if($request->hasFile('user_image')){
            if($profile->user_image == 'no-img.png'){
                $profile->user_image  = $fileNameToStore;
            }else{
                Storage::delete('public/user_images/'.$profile->user_image);
                $profile->user_image  = $fileNameToStore;
            }

            $posts                  = new Post;
            $posts->user_id         = auth()->user()->id;
            $posts->post_image      = $fileNameToStore;
            $posts->post_type        = 0;
            $posts->save();
        }
        $profile->save();

        $data = array(
            'profile'       => User::find(auth()->user()->id),
            'replies'       => Reply::orderBy('created_at', 'asc')->get(),
            'posts'         => Post::orderBy('created_at', 'desc')->get(),
            'comments'      => Comment::orderBy('created_at', 'asc')->get(),
            'alert_message' => 'Your Profile succesfully updated.',
            'post_update'   => 'changed profile picture.'
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
        //
    }
}
