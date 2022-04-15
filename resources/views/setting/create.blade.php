@extends('layouts.app')
@section('title','SETTING')
@section('content')

<div class="container">

    @if (session('message'))
    <div class="alert alert-success" role="alert">
        {{ session('message') }}
    </div>
    @endif

    <form method="POST" action='{{ route("settings.store") }}' enctype="multipart/form-data" class="setting">
        @csrf

        <div class="accordion">
            {{ __('CEDAR') }}
        </div>

        <div class="panel">

            <div class="form-group">
                <input class="form-control" type="text" name="site_name" placeholder="Site Name">
                @error('site_name')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="url" placeholder="URL eg: cedarmetadeta.org">
                @error('url')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="api_token" placeholder="API Token">
                @error('api_token')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="folder_id" placeholder="Folder Id">
                @error('folder_id')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="location" placeholder="location">
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
                <input class="form-control" type="text" name="hmis_username" placeholder="Username">
                @error('hmis_username')
                <label class="text-danger">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="hmis_password" placeholder="Password">
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

        <div class="accordion">
            {{ __('AllegroGraph') }}
        </div>

        <div class="panel">



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
        <div class="accordion">
            {{ __('Backup') }}
        </div>


        <div class="panel">



            <div class="form-group">
                <input class="form-control" type="text" name="backup_path" placeholder="Backup path">

            </div>

        </div>

        <div class="form-group" style="margin:10px">
            <button class="btn btn-success" type="submit">Save Settings</button>
        </div>

    </form>
</div>
@endsection