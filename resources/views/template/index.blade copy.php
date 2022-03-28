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
                            </tr>
                        </thead>
                        <tbody>
                        <form method="POST" action='{{ route("templates.store") }}' enctype="multipart/form-data">
                        @csrf
                        <tr>
                                <td>
                            @forelse($templates['properties'] as $key => $value)
                               @if ( in_array($key, $templates['_ui']['order']) && $value['_ui']['inputType'] == 'textfield' )
                               <div class="form-group">
                                  <input class="form-control" type="text" name="{{ $key }}" placeholder="{{ $key }}">                          
                                  @error('name')
                                    <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                               @elseif( in_array($key, $templates['_ui']['order']) && $value['_ui']['inputType'] == 'radio' )
                               <div class="form-group">
                               <select>
                                @foreach($templates['properties'][$key]['_valueConstraints']['literals'] as $k => $v)
                                    <option>{{ $v['label'] }}</opion>
                                @endforeach
                                @error('name')
                                    <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                                @elseif( in_array($key, $templates['_ui']['order']) && $value['_ui']['inputType'] == 'temporal' )
                                <div class="form-group">
                                    {{ $key }} <input class="form-control" type="date" name="{{ $key }}" placeholder="{{ $key }}">                          
                                    @error('name')
                                    <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>  
                                @endif
                            @empty

                            </td>
                               

                               </tr>
                              

                            <tr>
                                <td colspan="3" align="center">No records found!</td>
                            </tr>
                            @endforelse

<div class="form-group">
 <a class="btn btn-danger mr-1" href='{{ route("templates.index") }}' type="submit">Cancel</a>
<button class="btn btn-success" type="submit">Save</button>
</div>
</tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection