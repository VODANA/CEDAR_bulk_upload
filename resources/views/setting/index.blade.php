@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-14">
            <div class="card">
                <div class="card-header">{{ __('Settings') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <p><a class="btn btn-success" href='{{ route("settings.create") }}'><i class="fa fa-plus"></i>
                            Create setting</a></p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Field
                                </th>
                                <th>
                                    Value
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settings as $setting)
                            <tr>
                                <td>
                                    site_name
                                </td>
                                <td>
                                    {{ $setting->site_name ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    url
                                </td>
                                <td>
                                    {{ $setting->url ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    api_token
                                </td>

                                <td>
                                    {{ $setting->api_token ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    folder_id
                                </td>
                                <td>
                                    {{ $setting->folder_id ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    location </td>
                                <td>
                                    {{ $setting->location ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    created_at </td>

                                <td>
                                    {{ optional($setting->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a class="btn btn-success d-block mb-2"
                                        href='{{ route("settings.edit", $setting->id) }}'> Edit</a>
                                </td>
                                <td>
                                    <form method="POST" action='{{ route("settings.destroy", $setting->id) }}'>
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <input type="submit" class="btn btn-danger d-block" value="Delete">
                                    </form>
                                <td>

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