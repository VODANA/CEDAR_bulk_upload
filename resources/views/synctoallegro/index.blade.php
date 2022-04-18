@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Sync To Allegro Completed!!!') }}</div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                    <h3> {{ session('success') }}<h3>
                    </div>
                    @endif
                    @if (session('success'))
                             <h2> Add More RDF ... </h2>
                    @endif
                    <form method="POST" action='{{ route("synctoallegros.store") }}' enctype="multipart/form-data">
                        @csrf
                    <div class="form-group">
                            <textarea class="form-control" name="rdf" placeholder="RDF"></textarea> 
                            @error('rdf')
                            <label class="text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                     
                        <div class="form-group">
                            <a class="btn btn-danger mr-1" href='route("synctoallegros.store")' type="submit">Cancel</a>
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection