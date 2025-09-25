<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">

    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('logo.ico') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>-@yield('title')</title>
	<!-- Main Styles -->
    <link rel="stylesheet" href="{{ asset('dash/assets/fonts/cairo.css') }}">

	<link rel="stylesheet" href="{{ asset('front/assets/styles/style-horizontal.min.css')}}">
	
	<!-- Material Design Icon -->
	<link rel="stylesheet" href="{{ asset('front/assets/fonts/material-design/css/materialdesignicons.css')}}">

	<!-- mCustomScrollbar -->
	<link rel="stylesheet" href="{{ asset('front/assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css')}}">

	<!-- Waves Effect -->
	<link rel="stylesheet" href="{{ asset('front/assets/plugin/waves/waves.min.css')}}">

	<!-- Sweet Alert -->
	<link rel="stylesheet" href="{{ asset('front/assets/plugin/sweet-alert/sweetalert.css')}}">
	
	<!-- RTL -->
	<link rel="stylesheet" href="{{ asset('front/assets/styles/style-rtl.min.css')}}">
	<!-- Color Picker -->
	<link rel="stylesheet" href="{{ asset('front/assets/color-switcher/color-switcher.min.css')}}">
	<style>
        .dataTables_wrapper .dt-buttons {
            float: none;
            text-align: left;
        }

        .dataTables_wrapper .dataTables_filter {
            text-align: right;
            float: right;
        }
    </style>
    <script src="{{ asset('front/assets/scripts/jquery.min.js')}}"></script>
	<script src="{{ asset('front/assets/scripts/modernizr.min.js')}}"></script>
	<script src="{{ asset('front/assets/plugin/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{ asset('front/assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
	<script src="{{ asset('front/assets/plugin/nprogress/nprogress.j')}}s"></script>
	<script src="{{ asset('front/assets/plugin/sweet-alert/sweetalert.min.js')}}"></script>
	<script src="{{ asset('front/assets/plugin/waves/waves.min.js')}}"></script>
	<!-- Full Screen Plugin -->
	<script src="{{ asset('front/assets/plugin/fullscreen/jquery.fullscreen-min.js')}}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('dash/assets/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
	<script src="{{ asset('front/assets/scripts/horizontal-menu.min.js')}}"></script>
        <script src="{{ asset('sweetalert2@9') }}"></script>
		<style>
			.dataTables_wrapper .dt-buttons {
				float: none;
				text-align: left;
			}
		</style>
	
	<script src="{{ asset('front/search.js') }}"></script>

		<script src="{{ asset('dash/print/print.min.js') }}"></script>
		<link rel="stylesheet" type="text/css" href="{{ asset('dash/print/print.min.css') }}">
</head>

<body style="font-family:Cairo;">
<header class="fixed-header">
	<div class="header-top">
		<div class="container">
			<div class="pull-right">
				<a href="" class="logo"></a>
			</div>
			<!-- /.pull-right -->
			
			<!-- /.pull-left -->
		</div>
		<!-- /.container -->
	</div>
	<!-- /.header-top -->

	<!-- /.nav-horizontal -->
</header>
<!-- /.fixed-header -->

<div id="wrapper">
	<div class="main-content container">
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
<div class="title-area text-center">

    <h1>419</h1>
    <p>  الصفحة منتهية الصلاحية</p>
    </div>
        </div>
    </div>
</div>
</div>
</div>
<!--/#wrapper -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="assets/script/html5shiv.min.js"></script>
		<script src="assets/script/respond.min.js"></script>
	<![endif]-->
	<!-- 
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('front/assets/scripts/main.min.js')}}"></script>
	<script src="{{asset('front/html2canvas.js')}}"></script>

</body>
</html>
