@extends('layouts.app')
@section('title','HOME')
@section('content')
<div class="wrapper">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
                <h3> {{ session('success') }}<h3>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">CEDAR</span>
                                    <span class="info-box-number text-center text-muted mb-0"><a class="btn btn-link"
                                            href="https://cedar.metadatacenter.orgx/" target="_blanck">Login to
                                            CEDAR</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Dashboard</span>
                                    <span class="info-box-number text-center text-muted mb-0"><a class="btn btn-link"
                                            href="https://dashboard.vodana.health" target="_blanck">External</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">DHIS2</span>
                                    <span class="info-box-number text-center text-muted mb-0"><a class="btn btn-link"
                                            href="{{ url('/dhissyncs/create') }}" target="_blanck">Sync to
                                            DHIS2</a></span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4>CEDAR</h4>
                            <div class="post">
                            <h4 class="fas fa-link mr-1"> Templates</h4>

                                <!-- /.user-block --> <br/>
                                <p>
                                    => Total Templates in CEDAR: <span style="color:maroon">
                                    @if ($data['templates'])
                                        {{ count( $data['templates']) }} 
                                    @endif
                                </span>
                                </p>

                                <p>
                                    <a href="https://cedar.metadatacenter.org" class="link-black text-sm"><i
                                            class="fas fa-link mr-1"></i>
                                        CEDAR</a>
                                </p>
                            </div>


                            <h4 class="fas fa-link mr-1"> Template Instances</h4>
                            <div class="post">

                                <!-- /.user-block -->
              <!-- /.user-block -->
              <p>
                                    => Total Instances Created ... : <span style="color:maroon">
                                    @if ($data['templateinstance'])
                                        {{ count( $data['templateinstance']) }} 
                                    @endif
                                </span>
                                </p>

                            </div>

                            <h2> Linked Systems</h2>
                            <div class="post">
                            <div class="post">
                            <h4>  Healh Management Information System (HMIS)</h4>

                                <p>
                                    <a href="https://dhis2.org" class="link-black text-sm"><i
                                            class="fas fa-link mr-1"></i>
                                        DHIS2</a>
                                </p>
                            </div> <br/>

                                <!-- /.user-block -->
                                <h4>  Triple Store</h4>


                                <p>
                                    <a href="https://allegrograph.com" class="link-black text-sm"><i
                                            class="fas fa-link mr-1"></i>
                                        Allegrograph</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <h3 class="text-primary"><i class="fas fa-hospital"></i> Facility</h3>
                    <p class="text-muted">This facility name: <span style="color:maroon"> 
                    @if ($data['setting'])
                        {{ $data['setting'][0]['location'] }}
                    @endif
                 </span> </p>
                    <br>
                    <div class="text-muted">
                       <!-- <p class="text-sm">Client Company
                            <b class="d-block">Deveint Inc</b>
                        </p>
                        <p class="text-sm">Project Leader
                            <b class="d-block">Tony Chicken</b>
                        </p> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    </section>
    <!-- /.content -->
</div>



@endsection