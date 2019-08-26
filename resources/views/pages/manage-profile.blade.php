@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Manage Your Profile</div>
                
                    <div class="card-body">   
                        <form method="POST" action="/manage-profile/{{$profile->id}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                            <div class="row justify-content-center">
                                <div class="col-md-5"> 
                                    <img src="/storage/user_images/{{$profile->user_image}}" id="previewimg" class="rounded img-thumbnail" alt="" width="100%">
                               </div>
                            </div>
                            <div class="row justify-content-center">
                                <span class="btn btn-primary btn-file btn-sm">Change Profile Picture
                                    <input type="file" id="file" name="user_image">
                                </span>
                            </div>
                            <br>
                            
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('FullName* :') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $profile->name }}" required autocomplete="name"> 
                                </div>
                            </div>
        
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address* :') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $profile->email }}" required autocomplete="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="birthday" class="col-md-4 col-form-label text-md-right">{{ __('Birthday* :') }}</label>
                                <div class="col-md-6">
                                    <input id="birthday" type="date" class="form-control @error('birthday') is-invalid @enderror" name="birthday" value="{{$profile->birthday}}" required>
                                    
                                    @error('birthday')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address* :') }}</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{$profile->address}}" required>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Relationship status* :') }}</label>
                                <div class="col-md-6">
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="">Select your status</option>
                                        <option value="Single" @if($profile->status == "Single") selected @endif>Single</option>
                                        <option value="It's Complicated" @if($profile->status == "It's Complicated") selected @endif>It's Complicated</option>
                                        <option value="In a relationship" @if($profile->status == "In a relationship") selected @endif>In a relationship</option>
                                        <option value="Married" @if($profile->status == "Married") selected @endif>Married</option>
                                    </select>
                                    
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <input type="submit" name="submit" class="btn btn-primary col-md-2" value="Submit">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection