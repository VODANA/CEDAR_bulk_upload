<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VODAN | Africa</title>

    {{--
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    {{--<script nonce="734119ed-d016-48ee-9090-46c055c1fef3">
        (function(w,d){!function(a,e,t,r,z){a.zarazData=a.zarazData||{},a.zarazData.executed=[],a.zarazData.tracks=[],a.zaraz={deferred:[]};var s=e.getElementsByTagName("title")[0];s&&(a.zarazData.t=e.getElementsByTagName("title")[0].text),a.zarazData.w=a.screen.width,a.zarazData.h=a.screen.height,a.zarazData.j=a.innerHeight,a.zarazData.e=a.innerWidth,a.zarazData.l=a.location.href,a.zarazData.r=e.referrer,a.zarazData.k=a.screen.colorDepth,a.zarazData.n=e.characterSet,a.zarazData.o=(new Date).getTimezoneOffset(),a.dataLayer=a.dataLayer||[],a.zaraz.track=(e,t)=>{for(key in a.zarazData.tracks.push(e),t)a.zarazData["z_"+key]=t[key]},a.zaraz._preSet=[],a.zaraz.set=(e,t,r)=>{a.zarazData["z_"+e]=t,a.zaraz._preSet.push([e,t,r])},a.dataLayer.push({"zaraz.start":(new Date).getTime()}),a.addEventListener("DOMContentLoaded",(()=>{var t=e.getElementsByTagName(r)[0],z=e.createElement(r);z.defer=!0,z.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a.zarazData))),t.parentNode.insertBefore(z,t)}))}(w,d,0,"script");})(window,document);
    </script>--}}
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-white navbar-light">
            <!-- Left navbar links -->

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav" style="text-align:center;padding-left:20px;font-weight: bold;">
                @yield('title')
            </ul>

            <!-- Right navbar links -->
            <ul class="nav nav-tabs ml-auto">
                <!-- Messages Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false" style="margin-left: 1.5em;">
                        <i class="far fa-user"></i>
                        {{-- <p>{{ Auth::user()->name }}</p> --}}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2" style="min-width:1rem; top:40px;">
                        <li><a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
                        </li>
                    </ul>
                </div>
            </ul>
        </nav>
        {{-- /. navbar --}}
        <aside class="main-sidebar sidebar-light-primary elevation-1" style="background-color:lightgray">

            <img src="{{ asset('vodan_256.png') }}" alt="AdminLTE Logo" class="brand-image" style="opacity: 1">

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('Getu_.jpg') }}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"></a>
                    </div>
                </div>
                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('#') }}" class="nav-link active">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-upload"></i>
                                <p>
                                    Upload
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/templates') }}" class="nav-link">
                                        <i class="far fa-file-code nav-icon"></i>
                                        <p>Template Creation</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/bulkuploads') }}" class="nav-link">
                                        <i class="far fa-file-archive nav-icon"></i>
                                        <p>Bulk Upload</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/dhissyncs/create') }}" class="nav-link">
                                        <i class="far fa-file-archive nav-icon"></i>
                                        <p>DHIS2</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-download"></i>
                                <p>
                                    Backup
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/backups/create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-download nav-icon"></i>
                                        <p>Backup</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/upload/upload-file') }}" class="nav-link">
                                        <i class="nav-icon fas fa-window-restore nav-icon"></i>
                                        <p>Restore</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-sync-alt"></i>
                                <p>
                                    Sync
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/allegrosyncs')}}" class="nav-link">
                                        <i class="nav-icon fas fa-sync-alt nav-icon"></i>
                                        <p>Allegrograph Sync</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-sync-alt nav-icon"></i>
                                        <p>Allegrograph</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active" style="padding-left: 18px">
                                {{-- <i class="nav-icon fas fa-gear"></i> --}}
                                <i style="font-size:20px" class="fa">&#xf013;</i>
                                <p style="padding-left: 10px">
                                    Setting
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/settings') }}" class="nav-link">
                                        {{-- <i class="far fa-gear nav-icon"></i> --}}
                                        <i style="font-size:20px" class="fa">&#xf013;</i>
                                        <p style="padding-left: 10px">BulkUpload</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/allegrosettings')}}" class="nav-link">
                                        {{-- <i class="far fa-gear nav-icon"></i> --}}
                                        <i style="font-size:20px" class="fa">&#xf013;</i>
                                        <p style="padding-left: 10px">Allegrograph</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

        </aside>

        <div class="content-wrapper">

            <main class="py-1">
                @yield('content')
            </main>

        </div>

        <script src="{{asset('js/app.js')}}" defer></script>
        @stack('scripts')

</body>

</html>