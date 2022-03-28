@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Synced Data') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("allegrosyncs.update", $allegrosync->id) }}' enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="name" value="{{$allegrosync->name}}"> 
                            @error('name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                        <div class="form-group">
                            <input class="form-control" type="text" name="vocabulary_url" placeholder="Vocabulary URL" value="{{$allegrosync->vocabulary_url}}">
                            @error('vocabulary_url')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="folder_id" placeholder="folder_id" value="{{$allegrosync->folder_id}}">
                            @error('folder_id')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <input class="form-control" type="file" name="file_path" placeholder="File path CSV" value="{{$allegrosync->file_path}}">
                            @error('file_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="file" name="instancce_path" placeholder="Template instance JSON file" value="{{$allegrosync->instancce_path}}">
                            @error('instancce_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="form-group">
                            <a class="btn btn-danger mr-1" href='{{ route("allegrosyncs.index") }}' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection