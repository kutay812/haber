<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Admin Panel</title>

    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Stack: Styles -->
    @stack('styles')
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.index') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Haber Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - News -->
            <li class="nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.news.index') }}">
                    <i class="fas fa-fw fa-newspaper"></i>
                    <span>Haberler</span>
                </a>
            </li>

            <!-- Nav Item - Categories -->
            <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Kategoriler</span>
                </a>
            </li>

            <!-- Nav Item - Sources -->
            <li class="nav-item {{ request()->routeIs('admin.sources.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.sources.index') }}">
                    <i class="fas fa-fw fa-rss"></i>
                    <span>Haber Kaynakları</span>
                </a>
            </li>

            <!-- Nav Item - Tags -->
            <li class="nav-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.tags.index') }}">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Etiketler</span>
                </a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Kullanıcılar</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                @if(auth()->user()->profile_image)
                                    <img class="img-profile rounded-circle" src="{{ asset('storage/profile-image/' . auth()->user()->profile_image) }}">
                                @else
                                    <div class="img-profile rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.logout') }}" method="POST" class="dropdown-item">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts -->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Stack: Scripts -->
    @stack('scripts')
</body>
</html> 