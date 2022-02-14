@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Templates') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <p><a class="btn btn-success" href='{{ route("templates.create") }}'><i class="fa fa-plus"></i> Create Template</a></p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Folder Id
                                </th>
                                <th>
                                    Template Path
                                </th>
                                <th>
                                    Created
                                </th>
                                <th width="5%" colspan=2>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                            <tr>
                            <td>
                                    {{ $template->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $template->description ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $template->folder_id ?? 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{ $template->file_path ?? 'N/A' }}">{{ $template->name ?? 'N/A' }}</a>
                                </td>

                                <td>
                                    {{ optional($template->created_at)->diffForHumans() }}
                                </td>

                                <td >
                                    <a class="btn btn-success d-block mb-2" href='{{ route("templates.edit", $template->id) }}'> Edit</a>
                                </td>
                                <td >

                                <form method="POST" action='{{ route("templates.destroy", $template->id) }}'>
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