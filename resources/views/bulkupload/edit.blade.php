@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Bulk Input') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("bulkuploads.update", $bulkupload->id) }}' enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="name" value="{{$bulkupload->name}}"> 
                            @error('name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                        <div class="form-group">
                            <input class="form-control" type="text" name="vocabulary_url" placeholder="Vocabulary URL" value="{{$bulkupload->vocabulary_url}}">
                            @error('vocabulary_url')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="folder_id" placeholder="folder_id" value="{{$bulkupload->folder_id}}">
                            @error('folder_id')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <input class="form-control" type="file" name="file_path" placeholder="File path CSV" value="{{$bulkupload->file_path}}">
                            @error('file_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="file" name="instancce_path" placeholder="Template instance JSON file" value="{{$bulkupload->instancce_path}}">
                            @error('instancce_path')
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