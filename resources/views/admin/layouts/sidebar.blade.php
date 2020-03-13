<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
    <div class="logo"><a href="{{route('home')}}" class="simple-text logo-normal">
            Fleet-Management
        </a></div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item  {{(Request::is('admin/dashboard') ? 'active open' : '')}}">
                <a class="nav-link" href="{{route('home')}}">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item {{(Request::is('admin/trips*') ? 'active' : '')}} ">
                <a class="nav-link" href="{{route('trips.index')}}">
                    <i class="material-icons">content_paste</i>
                    <p>Trips</p>
                </a>
            </li>
        </ul>
    </div>
</div>

