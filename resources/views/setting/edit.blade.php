@extends('layouts.app')
@section('title','SETTING')
@section('content')
<div class="container">

    <form method="POST" action='{{ route("settings.store") }}' enctype="multipart/form-data" class="setting">
        @csrf

        <div class="accordion">
            {{ __('CEDAR') }}
        </div>

        <div class="panel">

            @method('PUT')

            <div class="form-group">
                <input class="form-control" type="text" name="site_name" placeholder="Site Name"
                    value="{{$setting->site_name}}">
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
                <input class="form-control" type="text" name="api_token" placeholder="API Token"
                    value="{{$setting->api_token}}">
                @error('api_token')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="folder_id" placeholder="Folder Id"
                    value="{{$setting->folder_id}}">
                @error('folder_id')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="location" placeholder="location"
                    value="{{$setting->location}}">
                @error('location')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>

        </div>

        <div class="accordion">
            {{ __('HMIS') }}
        </div>


        <div class="panel">

            <div class="form-group">
                <input class="form-control" type="text" name="hmis_username" placeholder="Username"
                    value="{{$setting->hmis_username}}">
                @error('hmis_username')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="hmis_password" placeholder="Password"
                    value="{{$setting->hmis_password}}">
                @error('hmis_password')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="hmis_url" placeholder="HMIS URL"
                    value="{{$setting->hmis_url}}">
                @error('hmis_url')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>

        </div>

        <div class="accordion">
            {{ __('AllegroGraph') }}
        </div>

        <div class="panel">

            <div class="form-group">
                <input class="form-control" type="text" name="allegro_username" placeholder="Username"
                    value="{{$setting->allegro_username}}">
                @error('allegro_username')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="allegro_password" placeholder="Password"
                    value="{{$setting->allegro_password}}">
                @error('allegro_password')
                <label class=" text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="allegro_url" placeholder="AllegroGraph URL"
                    value="{{$setting->allegro_url}}">
                @error('allegro_url')
                <label class=" text-danger">{{ $message }}</label>
                @enderror
            </div>

        </div>
        <div class="accordion">
            {{ __('Backup') }}
        </div>
        <div class="panel">
            <div class="form-group">
                <input class="form-control" type="text" name="backup_path" placeholder="Backup path"
                    value="{{$setting->backup_path}}">
                @error('allegro_username')
                <label class=" text-danger">{{ $message }}</label>
                @enderror
            </div>
        </div>


        <div class="form-group">
            <a class="btn btn-danger mr-1" href='{{ route("settings.index") }}' type="submit">Cancel</a>
            <button class="btn btn-success" type="submit">Save</button>
        </div>

    </form>



</div>
@endsection