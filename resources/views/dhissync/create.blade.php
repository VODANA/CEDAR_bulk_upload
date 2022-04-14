@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Sync to DHIS2') }}</div>

                <div class="card-body">

                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("backups.store") }}' enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <input class="form-control" type="hidden" name="backup_name" placeholder="Backup Name" value="CEDAR">
                            @error('backup_name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="description" placeholder="description"></textarea> 
                            @error('description')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="file" name="backup_dir" placeholder="Backup Path">
                            @error('backup_path')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="hidden" name="database_name" placeholder="Database Name" value="cedar">
                            @error('database_name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="form-group">
                            <a class="btn btn-danger mr-1" href='{{ route("backups.store") }}' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection