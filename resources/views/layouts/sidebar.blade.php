<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('web.todos.index') }}" class="logo logo-dark">
            <span class="logo-sm">
                <i class="ri-task-line text-primary fs-22"></i>
            </span>
            <span class="logo-lg">
                <h4 class="text-white fw-bold mt-3"><i class="ri-task-line text-primary me-2"></i>Todo App</h4>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('web.todos.index') }}" class="logo logo-light">
            <span class="logo-sm">
                <i class="ri-task-line text-primary fs-22"></i>
            </span>
            <span class="logo-lg">
                <h4 class="text-white fw-bold mt-3"><i class="ri-task-line text-primary me-2"></i>Todo App</h4>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('web.todos.index') ? 'active' : '' }}" href="{{ route('web.todos.index') }}">
                        <i class="ri-task-line"></i> <span>Todo Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                        <i class="ri-user-line"></i> <span>My Profile</span>
                    </a>
                </li>
                <li class="menu-title"><span>Actions</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                        <i class="ri-logout-box-r-line"></i> <span>Logout</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<div class="vertical-overlay"></div>
