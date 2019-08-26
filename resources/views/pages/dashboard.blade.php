@extends('layouts.app')

@section('content')
<div class="container col-md-8">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Basic Info</div>
                    <div class="card card-body">
                        <div class="row justify-content-center"> 
                            <img src="storage/user_images/{{$profile->user_image}}" class="rounded" alt="" width="180" height="195">   
                        </div>
                        <div class="row justify-content-center prof_name">
                            <b>{{$profile->name}}</b>
                        </div>
                        <br>
                        @if(($profile->birthday && $profile->address && $profile->status) == NULL)
                            <a href="manage-profile/{{$profile->id}}/edit"class="btn btn-primary col-md-12">Add More Info About You</a>
                        @else
                            <div class="form-group">
                                Lives in {{$profile->address}}
                                <br>
                                Born on {{date('F d Y', strtotime($profile->birthday))}}
                                <br>
                                {{$profile->status}}
                            </div>
                            <div class="form-group">
                                <a href="manage-profile/{{$profile->id}}/edit"class="btn btn-primary col-md-12">Edit Profile</a>
                            </div>
                        @endif
                    </div>
            </div>
        </div>

        <!----Display Profile Information---->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="/dashboard" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <textarea name="post_content" class="form-control @error('post_content') is-invalid @enderror" id="post_content" placeholder="What's on your mind"></textarea>
                            @error('post_content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <img src="" id="preview_post_img" alt="" width="105px;" height="110px;">
                            <br id="img-br">
                            <span class="btn btn-primary btn-file btn-sm" id="file">Upload Photo
                                <input type="file" id="file" name="post_image" value="">
                            </span>
                        </div>
                        <input type="submit" name="submit" value="Submit" class="btn btn-success btn-sm col-md-2">     
                    </form>
                    @include('inc.alert-message')
                </div>
            </div>

            <!----Display Authenticated User's Post---->
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    @if($post->user_id == $profile->id)
                        <div class="card">
                            <div class="card-body">
                                
                                <div style="display:flex;">
                                    <div>
                                        <img src="storage/user_images/{{$post->user->user_image}}" alt="" id="button-addon-3" aria-label="Post a comment" aria-describedby="button-addon-1" height = "37px" width = "37px">
                                    </div>
                                    <div style="padding-left: 10px;">
                                        <p>
                                            <b>{{$post->user->name}}</b>
                                            @if($post->post_type == 0)
                                                &nbspchanged profile picture.
                                            @endif
                                        </p>
                                    </div>

                                    <div style="right: 20px; position:absolute">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="ture" aria-expanded="false">
                                            <small>Action</small>
                                        </button>

                                        <div class="dropdown-menu">
                                            <button class="dropdown-item" type="button" data-toggle="modal" data-target="#exampleModalEdit{{$post->id}}">
                                                Edit
                                            </button>

                                            <button class="dropdown-item" type="submit" data-toggle="modal" data-target="#exampleModalDelete{{$post->id}}">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p class="p-timestamp">{{$post->created_at->diffForHumans()}}</p>
                                <br>

                                <!-------Modal for edit post------>
                                <div class="modal fade" id="exampleModalEdit{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterEdit" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalCenterEdit">Edit Post</h6>
                                                <button class="close" data-dismiss="modal" aria-label="close">
                                                    <span aria-hidden="true">&times</span>
                                                </button>
                                            </div>

                                            <form action="/dashboard/{{$post->id}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <textarea name="post_content" class="form-control" placeholder="Say something about this post">{{$post->post_content}}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <img src="/storage/post_images/{{$post->post_image}}" id="previewimg-edit{{$post->id}}" class="rounded img_edit-content" alt="">
                                                        <br id="img-br">
                                                        <span class="btn btn-primary btn-file btn-sm">
                                                            @if(empty($post->post_image))
                                                                Upload Photo
                                                            @else
                                                                Change Photo
                                                            @endif
                                                            <input type="file" id="file-edit" name="post_image">
                                                        </span>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="row justify-content-center">
                                                            <input type="submit" name="submit" value="Update Post" class="btn btn-success col-md-5">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-------Modal for delete post------>
                                <div class="modal fade" id="exampleModalDelete{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCentertitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalCenterTitle">Delete Post</h6>
                                                <button class="close" data-dismiss="modal" aria-label="close">
                                                    <span aria-hidden="true">&times</span>
                                                </button>
                                            </div>
         
                                            <div class="modal-body">
                                                Are you sure you want to delete this post?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" >Cancel</button>
                                                
                                                <form action="dashboard/{{$post->id}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" name="submit" class="btn btn-primary btn-sm">Continue</button>
                                                </form>
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                                
                                <!-------Display Post Content------>
                                <span>{{$post->post_content}}</span>
                                @if($post->post_image == NULL)
                                    <br><br>
                                @else

                                    <!----Check if image is portrait or landscape---->
                                    <div class="div-img">
                                        @php
                                            list($width, $height) = getimagesize(public_path('/storage/post_images/'.$post->post_image));
                                            if ($width > $height) {
                                                $orientation = "landscape";
                                            } else {
                                                $orientation = "portrait";
                                            }
                                        @endphp
                                        <img src="storage/post_images/{{$post->post_image}}" alt="" class="rounded {{$orientation}}">       
                                    </div>

                                @endif
        
                                <div class="form-group">
                                    <button class="btn btn-secondary btn-sm" type="button" data-toggle="collapse" data-target="#collapseComment{{$post->id}}" aria-expanded="false" aria-controls="collapseComment{{$post->id}}">
                                        Comments <span class="badge badge-primary">{{count(App\Comment::where('post_id', $post->id)->get()) + count(App\Reply::where('post_id', $post->id)->get())}}</span>
                                    </button>
                                    
                                </div>
                                <hr>
        
                                <!-------Display Comments to a specific post------>
                                <div class="collapse" id="collapseComment{{$post->id}}">
                                    @foreach($comments as $comment)
                                        @if($comment->post_id == $post->id)
                                            <div style="margin-bottom:5px;" id="heading{{$comment->id}}">
                                                <div class="comment-container">
                                                    <div>
                                                        <img src="storage/user_images/{{$comment->user->user_image}}" alt="" id="rep-pic" height = "33px" width = "33px">
                                                    </div>
                                                    <div class="alert custom-comment-alert">
                                                        <span><b>{{$comment->user->name . ': '}}</b>{{$comment->comments}}</span>
                                                    </div>
                                                </div>
        
                                                <div class="btn-comment-container">
                                                    <a href="#" class="" data-toggle="collapse" data-target="#collapseReply{{$comment->id}}" aria-expanded="false" aria-controls="collapseReply{{$comment->id}}">
                                                        <span>{{count(App\Reply::where('comment_id', $comment->id)->get())}}</span>
                                                        Reply</a>&nbsp
        
                                                    @if($comment->user_id == $profile->id)
                                                        <a href=""  class="" data-toggle="modal" data-target="#exampleModalDeleteComment{{$comment->id}}">Delete</a>&nbsp
                                                    @else
                                                        <a href="#"  class="disabled">Delete</a>&nbsp
                                                    @endif
        
                                                    <a href="#" class="">Like</a>&nbsp;
                                                    {{$comment->created_at->diffForHumans()}}
                                                </div>
                                            </div>
        
                                            <!-------Modal for delete comment------>
                                            <div class="modal fade" id="exampleModalDeleteComment{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCentertitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title" id="exampleModalCenterTitle">Delete Post</h6>
                                                            <button class="close" data-dismiss="modal" aria-label="close">
                                                                <span aria-hidden="true">&times</span>
                                                            </button>
                                                        </div>
                        
                                                        <div class="modal-body">
                                                            Are you sure you want to delete your comment?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" >Cancel</button>
                                                            
                                                            <form action="home/{{$comment->id}}" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" name="submit" class="btn btn-primary btn-sm">Continue</button>
                                                            </form>
                                                        </div>
                                                    </div>      
                                                </div>
                                            </div>
        
                                            <!-------Display Replies to a specific comments and post------>
                                            <div class="rep-section collapse" id="collapseReply{{$comment->id}}">
                                                @foreach($replies as $reply)
                                                    @if($reply->comment_id == $comment->id)
                                                        <div class="reply-container">
                                                            <div>
                                                                <img src="storage/user_images/{{$reply->user->user_image}}" alt="" id="rep-pic" height = "27px" width = "27px">
                                                            </div>
                                                            <div class="alert custom-reply-alert">
                                                                <span><b>{{$reply->user->name . ': '}}</b>{{$reply->replies}}</span>
                                                            </div>
                                                        </div>
        
                                                        <div class="btn-reply-container">
        
                                                            @if($reply->user_id == $profile->id)
                                                                <a href=""  class="" data-toggle="modal" data-target="#exampleModalDeleteReply{{$reply->id}}">Delete</a>
                                                            @else
                                                                <a href="#"  class="disabled">Delete</a>
                                                            @endif
                                                                &nbsp
                                                            <a href="#" class="">Like</a>&nbsp;
                                                            {{$reply->created_at->diffForHumans()}}
                                                        </div>
        
                                                        <!-------Modal for delete comment------>
                                                        <div class="modal fade" id="exampleModalDeleteReply{{$reply->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCentertitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h6 class="modal-title" id="exampleModalCenterTitle">Delete Post</h6>
                                                                        <button class="close" data-dismiss="modal" aria-label="close">
                                                                            <span aria-hidden="true">&times</span>
                                                                        </button>
                                                                    </div>
                                    
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" >Cancel</button>
                                                                        
                                                                        <form action="home/{{$reply->id}}/delete" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" name="submit" class="btn btn-primary btn-sm">Continue</button>
                                                                        </form>
                                                                    </div>
                                                                </div>      
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                
                                                <form action="home/{{$post->id}}" method="post">
                                                    @csrf
                                                    <div class="reply-form-container">
                                                        <img src="storage/user_images/{{$profile->user_image}}" alt="" id="rep-pic" height = "27px" width = "27px">
                                                    
                                                        <div class="input-group reply-form">
                                                            <input type="text" class="form-control" name="reply" placeholder="Post your reply" aria-label="Post your reply" aria-describedby="button-addon" required>
                                                            <input type="hidden" name="comment_id" value="{{$comment->id}}">
                                                            <input type="hidden" name="post_id" value="{{$post->id}}">
                                                            <div class="input-group-append reply-form">
                                                                <button class="btn btn-secondary" type="submit" name="submit" id="button-addon">Post</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
        
                                <form action="/home" method="post">
                                @csrf
                                    <div style="display:flex;">
                                        <img src="storage/user_images/{{$profile->user_image}}" alt="" id="rep-pic" height = "33px" width = "33px">
                                    
                                        <div class="input-group reply-form">
                                            <input type="text" class="form-control" name="comments" placeholder="Post a comment" aria-label="Post a comment" aria-describedby="button-addon-1" required>
                                            <input type="hidden" name="post_id" value="{{$post->id}}">
                                            <div class="input-group-append reply-form">
                                                <button class="btn btn-secondary" type="submit" name="submit" id="button-addon-1">Post</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
        
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

        </div>
    </div>
</div>
@endsection