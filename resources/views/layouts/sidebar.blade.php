<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="pm-logo">J</span>
        <span class="brand-text">JOM MUSHEEDA</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Signed-in user -->
        <div class="pm-user">
            <span class="pm-user-initial">{{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
            <div class="pm-user-text" style="min-width:0">
                <div class="pm-user-name">{{ Auth::user()->name }}</div>
                <div class="pm-user-role">Signed in</div>
            </div>
        </div>

        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @yield('dashboard')">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header pm-nav-header">Catalogue</li>
                <li class="nav-item">
                    <a href="{{ route('item.index') }}" class="nav-link @yield('item')">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Particular / Items</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('size.index') }}" class="nav-link @yield('size')">
                        <i class="nav-icon fas fa-ruler-combined"></i>
                        <p>Size</p>
                    </a>
                </li>

                <li class="nav-header pm-nav-header">People</li>
                <li class="nav-item">
                    <a href="{{ route('employee.index') }}" class="nav-link @yield('employee')">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Employee / Karigar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('contractor.index') }}" class="nav-link @yield('contractor')">
                        <i class="nav-icon fas fa-hard-hat"></i>
                        <p>Employee / Thekedar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('master.index') }}" class="nav-link @yield('master')">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Master</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('party.index') }}" class="nav-link @yield('party')">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>Parties</p>
                    </a>
                </li>

                <li class="nav-header pm-nav-header">Account</li>
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link @yield('profile')">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item pm-logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
