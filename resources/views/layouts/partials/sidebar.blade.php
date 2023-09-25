<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">
                <img src="{{ url($setting->logo) }}" width="50">
                <p>{{ $setting->nama_website }}</p>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}"><img src="{{ url($setting->logo) }}" width="50"></a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li @if(Request::segment(1)=='dashboard') class="active" @endif><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>

            <li class="menu-header">Page</li>
            @if(Auth::user()->role == 'Petugas' || Auth::user()->role == 'Administrator')
                <li @if(Request::segment(1)=='profile') class="active" @endif><a class="nav-link" href="{{ route('profile') }}"><i class="fas fa-users"></i> <span>Pemohon SIM</span></a></li>
            @else
                <li @if(Request::segment(1)=='profile') class="active" @endif><a class="nav-link" href="{{ route('profile') }}"><i class="fas fa-user"></i> <span>Profile</span></a></li>
            @endif

            @if(Auth::user()->role == 'Petugas')
                <li @if(Request::segment(1)=='petugas') class="active" @endif><a class="nav-link" href="{{ route('petugas') }}"><i class="fas fa-user"></i> <span>Petugas</span></a></li>
            @endif
            
            @if(Auth::user()->role == 'Administrator')
                <li @if(Request::segment(1)=='petugas') class="active" @endif><a class="nav-link" href="{{ route('petugas') }}"><i class="fas fa-users"></i> <span>Petugas</span></a></li>
                <li @if(Request::segment(1)=='polres') class="active" @endif><a class="nav-link" href="{{ route('polres') }}"><i class="fas ion-ios-home"></i> <span>Polres</span></a></li>
                <li @if(Request::segment(1)=='jabatan') class="active" @endif><a class="nav-link" href="{{ route('jabatan') }}"><i class="fas ion-navicon-round"></i> <span>Jabatan</span></a></li>
                
                <li class="menu-header">Settings</li>
                <li @if(Request::segment(1)=='user') class="active" @endif><a href="{{ route('user') }}" class="nav-link"><i class="fas fa-users"></i> <span>Users</span></a></li>
                <li @if(Request::segment(1)=='setting') class="active" @endif><a href="{{ route('setting') }}" class="nav-link"><i class="fas ion-ios-gear"></i> <span>Setting Website</span></a></li>
            @endif
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ route('logout') }}" class="btn btn-danger btn-lg btn-block btn-icon-split">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </div>
    </aside>
</div>
