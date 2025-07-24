<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }} - Admin Panel</title>

    <!-- Custom fonts -->
    <link href="/storage/adminlte/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="/storage/adminlte/css/adminlte.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f1c40f;
            --info-color: #3498db;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f5f6fa;
            font-family: 'Nunito', sans-serif;
            color: var(--dark-color);
        }
        
        #wrapper {
            display: flex;
            flex: 1;
            background-color: #f5f6fa;
        }
        
        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #f5f6fa;
            overflow-x: hidden;
        }
        
        #content {
            flex: 1;
            padding: 1.5rem;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            background: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.2rem 1.5rem;
            border-radius: 8px 8px 0 0 !important;
        }

        .card-header h6 {
            margin: 0;
            color: var(--dark-color);
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Styles */
        .form-control {
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.15);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        /* Button Styles */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            padding: 1rem;
            background: rgba(0,0,0,0.02);
            color: var(--dark-color);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: rgba(0,0,0,0.05);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(46,204,113,0.1);
            color: #27ae60;
        }

        .alert-danger {
            background: rgba(231,76,60,0.1);
            color: #c0392b;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5em 1em;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-primary {
            background: rgba(52,152,219,0.1);
            color: var(--primary-color);
        }

        .badge-success {
            background: rgba(46,204,113,0.1);
            color: var(--success-color);
        }

        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            display: none;
            width: 2.75rem;
            height: 2.75rem;
            text-align: center;
            color: #fff;
            background: var(--primary-color);
            border-radius: 8px;
            line-height: 46px;
            transition: all 0.3s ease;
            opacity: 0.8;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .scroll-to-top:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            #content {
                padding: 1rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .card-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .table-responsive {
                margin: 0 -1rem;
                width: calc(100% + 2rem);
            }
        }
    </style>
    
    @stack('styles')
</head>

<body id="page-top">
    @auth
    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('layouts.admin.sidebar')
        <!-- Content Wrapper -->
        <div id="content-wrapper">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top" href="#page-top">
        <i class="fas fa-chevron-up"></i>
    </a>
    @else
        @yield('content')
    @endauth

    <!-- Core JavaScript-->
    <script src="/storage/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="/storage/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/storage/adminlte/plugins/jquery-easing/jquery.easing.min.js"></script>
    <script src="/storage/adminlte/js/adminlte.min.js"></script>

    <script>
        // Scroll to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }
        });

        $('.scroll-to-top').click(function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 500, 'easeInOutExpo');
        });

        // Alert auto-close
        window.setTimeout(function() {
            $('.alert').fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    </script>

    @stack('scripts')
</body>
</html>
