@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Setting') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("settings.update", $setting->id) }}' enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <input class="form-control" type="text" name="site_name" placeholder="Site Name" value="{{$setting->site_name}}"> 
                            @error('site_name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="url" placeholder="URL" value="{{$setting->url}}">
                            @error('url')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="api_token" placeholder="API Token" value="{{$setting->api_token}}">
                            @error('api_token')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="folder_id" placeholder="Folder Id" value="{{$setting->folder_id}}">
                            @error('folder_id')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="location" placeholder="location" value="{{$setting->location}}">
                            @error('location')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                      


                        <button class="accordion"> <h3>{{ __('HMIS') }} </h3> </button>


<div class="panel">
<div class="card-header"><h3>{{ __('HMIS') }} </h3></div>

<div class="form-group">
    <input class="form-control" type="text" name="hmis_username" placeholder="Username" value="{{$setting->hmis_username}}">
    @error('hmis_username')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <input class="form-control" type="password" name="hmis_password" placeholder="Password"  value="{{$setting->hmis_password}}">
    @error('hmis_password')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <input class="form-control" type="text" name="hmis_url" placeholder="HMIS URL">
    @error('hmis_url')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>
</div>
<button class="accordion"> <h3>{{ __('AllegroGraph') }} </h3> </button>

<div class="card-header"><h3>{{ __('AllegroGraph') }} </h3></div>

<div class="card-body">

<div class="form-group">
    <input class="form-control" type="text" name="allegro_username" placeholder="Username">
    @error('allegro_username')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <input class="form-control" type="password" name="allegro_password" placeholder="Password">
    @error('allegro_password')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <input class="form-control" type="text" name="allegro_url" placeholder="AllegroGraph URL">
    @error('allegro_url')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>
</div>
<button class="accordion"> <h3>{{ __('Backup') }} </h3> </button>

<div class="card-header"><h3>{{ __('Backup') }} </h3></div>

<div class="card-body">

<div class="form-group">
    <input class="form-control" type="text" name="backup_path" placeholder="Backup path">
    @error('allegro_username')
    <label class="text-danger">{{ $message }}</label>
    @enderror
</div>

<div class="form-group">
                            <a class="btn btn-danger mr-1" href='{{ route("settings.index") }}' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
