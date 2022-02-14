@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Bulkuploads') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <p><a class="btn btn-success" href='{{ route("bulkuploads.create") }}'><i class="fa fa-plus"></i> Upload Bulk Input</a></p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                              <!--  <th>
                                    Source File
                                </th>
                                <th>
                                    Template Instance   
                                </th> -->
                                <th>
                                    Vocabulary URL
                                </th>
                                <th>
                                    Folder Id
                                </th>
                                <th>
                                    Created
                                </th>
                                <th width="5%" colspan=2>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bulkuploads as $bulkupload)
                            <tr>
                            <td>
                                    {{ $bulkupload->name ?? 'N/A' }}
                                </td>
                              <!--  <td>
                                <a href="{{ $bulkupload->file_path ?? 'N/A' }}"> Download</a>
                                </td>
                                <td>
                                    <a href="{{ $bulkupload->instance_path ?? 'N/A' }}">Download</a>
                                </td> -->
                                <td>
                                    {{ $bulkupload->vocabulary_url ?? 'N/A' }}
                                </td>
                              
                                <td>
                                    {{ $bulkupload->folder_id ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ optional($bulkupload->created_at)->diffForHumans() }}
                                </td>

                                <td >
                                    <a class="btn btn-success d-block mb-2" href='{{ route("bulkuploads.edit", $bulkupload->id) }}'> Edit</a>
                                </td>
                                <td >

                                <form method="POST" action='{{ route("bulkuploads.destroy", $bulkupload->id) }}'>
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                            <input type="submit" class="btn btn-danger d-block" value="Delete">
                                </form>
                                <td >
                            
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" align="center">No records found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection