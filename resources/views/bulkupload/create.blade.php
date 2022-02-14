@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Upload Bulk Input') }}</div>

                <div class="card-body">

                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("bulkuploads.store") }}' enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="name">
                            @error('name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="vocabulary_url" placeholder="Vocabulary URL">
                            @error('vocabulary_url')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="folder_id" placeholder="folder_id">
                            @error('folder_id')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>  
                        <div class="form-group">
                            <label> Path of CSV file to be uploaded</label>
                            <input class="form-control" type="file" name="file_path" placeholder="File path CSV">
                            @error('file_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Template instance JSON file</label>
                            <input class="form-control" type="file" name="instance_path" placeholder="Template instance JSON file">
                            @error('instance_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>                    
                        <div class="form-group">
                            <a class="btn btn-danger mr-1" href='{{ route("bulkuploads.index") }}' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection