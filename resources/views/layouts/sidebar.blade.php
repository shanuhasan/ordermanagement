<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">JCM MUSHEEDA</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link"
                        style="background: green;color:#fff;font-weight:bold;font-size:24px;text-align:center">
                        <p>{{ Auth::user()->name }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @yield('dashboard')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.index') }}" class="nav-link @yield('employee')">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Employee</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('items.index') }}" class="nav-link @yield('items')">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Items</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link @yield('profile')">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link"
                            onclick="event.preventDefault();
                        this.closest('form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
