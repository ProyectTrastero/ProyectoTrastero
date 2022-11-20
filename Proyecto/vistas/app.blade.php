<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <!--<link rel="stylesheet" href="asset/css/style.css" >
        <link rel="stylesheet" href="asset/css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="asset/css/all.css">-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    </head>
     <body>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
			<div class="container-fluid">
                            <a class="navbar-brand">
				<img src="asset/img/logo.png" alt="" width="80" height="80">
				Mitrastero.com
                            </a>
                            
                           @yield('navbar')
			</div>
        </nav>
        @yield('content')

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="asset/js/jquery/jquery-3.6.0.min.js"></script>
        <script src="asset/js/modal.js"></script>
        <script src="asset/js/alerts.js"></script>
    </body>
</html>