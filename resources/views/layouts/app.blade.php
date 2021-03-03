<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/vendor/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/vendor/select2/select2-bootstrap4.css')}}">
    @yield('css')
</head>
<body>
    <div id="app">
    <!-- ======= Top Bar ======= -->
    <div id="topbar" class="d-none d-lg-flex align-items-center fixed-top">
        <div class="container d-flex">
            <div class="contact-info mr-auto d-flex align-items-center ">
                Welcome, <b>&nbsp; {{Auth::user()->name}}</b>
            </div>
            <div class="social-links">
                <div class="position-relative d-flex align-items-center">
                    <input class="form-control form-control-sm w-400 input-search" type="text" placeholder="Search UID / SIM / GPID / Invite Code">
                    <i data-feather="search" class="text-search"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <a href="/" class="logo mr-auto"><img src="{{url('/assets/images/logo.png')}}" alt=""> Cashtree</a>

            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="index.html">Home</a></li>
                    <li><a href="#">Campaigns</a></li>
                    <li><a href="#">Analytics</a></li>
                    <li><a href="#">Data</a></li>
                    <li class="drop-down"><a href="javascript:void(0)">Master</a>
                        <ul>
                            <li><a href="{{url('/admin/master/province')}}">Province</a></li>
                            <li><a href="{{url('/admin/master/city')}}">City</a></li>
                            <li><a href="{{url('/admin/master/company-category')}}">Company Category</a></li>
                        </ul>
                    </li>
                    <li class="drop-down"><a href="javascript:void(0)">Features</a>
                        <ul>
                            <li class="drop-down"><a href="javascript:void(0)">Job Part Time</a>
                                <ul>
                                    <li><a href="{{url('/admin/part-time/vacancy')}}">Vacancy</a></li>
                                    <li><a href="{{url('/admin/part-time/company')}}">Company</a></li>
                                    <li><a href="{{url('/admin/part-time/applicant')}}">Applicants</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Drop Down 2</a></li>
                            <li><a href="#">Drop Down 3</a></li>
                            <li><a href="#">Drop Down 4</a></li>
                        </ul>
                    </li>
                    <li class="drop-down"><a href="">Operational</a>
                        <ul>
                            <li><a href="#">Drop Down 1</a></li>
                            <li><a href="{{url('/admin/operational/clbk/event')}}">CLBK Event</a></li>
                            <li><a href="#">Drop Down 2</a></li>
                            <li><a href="#">Drop Down 3</a></li>
                            <li><a href="#">Drop Down 4</a></li>
                        </ul>
                    </li>
                    <li class="drop-down"><a href="javascript:void(0)">Settings</a>
                        <ul>
                            <li><a href="{{url('/admin/settings/change-password')}}">Change Password</a></li>
                            <li><a href="{{url('/admin/settings/user-admin')}}">User Management</a></li>
                            <li><a href="#">Email Signature</a></li>
                            <li>
                                <a href="{{ route('logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();" >
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav><!-- .nav-menu -->

        </div>
    </header><!-- End Header -->

    <!-- ======= Content ======= -->
    <main class="py-4" style="margin-top: 130px">
        @yield('content')
    </main>
    <!-- End Content -->
    </div>

    <div id="loading" class="loading hide">
        <p>please wait ...</p>
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace()
    </script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js" type="text/javascript"></script>
    <script src="{{url('/vendor/select2/select2.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
    <script src="{{url('/vendor/aos/aos.js')}}"></script>
    <script src="{{url('/js/utils.js')}}"></script>
    <script>
        $(document).ready( function () {
            $('.datatable').DataTable();
            $('.select2').select2({
                theme: "bootstrap4"
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <!-- Template Main JS File -->
    <script src="{{url('/js/main.js')}}"></script>
    @yield('js')
</body>
</html>
