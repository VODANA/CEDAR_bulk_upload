@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>{{ __('Sync CEDAR to AllegroGraph') }}</h1></div>

                <div class="card-body">

                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action='{{ route("synccedartoallegros.store") }}' enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <select class="form-control" name="template_name">
                                <!-- default: user -->
                                <option value="All">Select Template To Sync To AllegrGraph</option>
                                <option value="All">Sync All To AllegrGraph</option>
                                <option value="Antenatal">Sync Antenatal Care Template</option>
                                <option value="Out">Sync Out Patient Template</option>
                                <option value="Covid">Sync Covid Template</option>
                            </select>
                            @error('template_name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" name="repository" placeholder="Allegro reporistory name">
                            @error('name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>        
                       <!-- <div class="form-group">
                            <input class="form-control" type="text" name="repository" placeholder="Allegro reporistory name">
                            @error('name')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>        
                        <div class="form-group">
                            <textarea class="form-control" name="rdf" placeholder="RDF"></textarea> 
                            @error('rdf')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div> -->

                        
                     
                        <div class="form-group">
                            <a class="btn btn-danger mr-1" href='route("synccedartoallegros.store")' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection