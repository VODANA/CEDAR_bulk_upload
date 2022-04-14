@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>{{ __('Sync to DHIS') }}</h2></div>

                <div class="card-body">

                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("dhissyncs.store") }}' enctype="multipart/form-data">
                        @csrf

                       <!-- <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="Reporting Period">
                            @error('name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="vocabulary_url" placeholder="Repository URL">
                            @error('vocabulary_url')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div> -->

                        <div class="form-group"><br/>
                            <label> Path of CSV file to be uploaded</label>
                            <input class="form-control" type="file" name="file_path" placeholder="DHIS2 Reporting Template JSON">
                            @error('file_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>                   
                        <div class="form-group"><br/>
                           <!-- <a class="btn btn-danger mr-1" href='{{ route("dhissyncs.store") }}' type="submit">Cancel</a>-->
                            <button class="btn btn-success" type="submit">Sync To DHIS2</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection