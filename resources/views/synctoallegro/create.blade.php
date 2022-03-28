@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Sync to AllegroGraph') }}</div>

                <div class="card-body">

                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("synctoallegros.store") }}' enctype="multipart/form-data">
                        @csrf
                       <!-- <div class="form-group">
                            <textarea class="form-control" name="rdf" placeholder="RDF"></textarea> 
                            @error('rdf')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>-->
                        @forelse($templates['properties'] as $key => $value)
                               @if ( in_array($key, $templates['_ui']['order']) && ($value['_ui']['inputType'] == 'textfield' || $value['_ui']['inputType'] == 'numeric'))
                               <div class="form-group">
                                  <input class="form-control" type="text" name="{{ $key }}" placeholder="{{ $key }}">                          
                                  @error('name')
                                    <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                               @elseif( in_array($key, $templates['_ui']['order']) && $value['_ui']['inputType'] == 'radio' )
                               <div class="form-group">
                               {{ $key }} <select name="{{ $key }}">
                                @foreach($templates['properties'][$key]['_valueConstraints']['literals'] as $k => $v)
                                    <option value="{{ $k }}">{{ $v['label'] }}</opion>
                                @endforeach
                                </select>
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

                               
                            @endforelse                  
                        <div class="form-group">
                            <a class="btn btn-danger mr-1" href='{{ route("synctoallegros.index") }}' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection