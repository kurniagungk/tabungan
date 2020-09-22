<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('/css/sb-admin-2.min.css')}}" rel="stylesheet">
    @livewireStyles


    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="{{asset('/js/sb-admin-2.min.js')}}"></script>
    @livewireScripts

    @stack('scripts')

    @yield('css')




</head>

<body class="bg-primary">
    <div class="container">
        <!-- Page Wrapper -->
        @yield('content')
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->

    </div>
    <!-- Logout Modal-->

    <!-- Bootstrap core JavaScript-->


    <!-- Custom scripts for all pages-->






</body>

</html>