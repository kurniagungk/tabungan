<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">




    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="http://tabungan.alkahfi-somalangu.id">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">E-Santri <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
        <a class="nav-link" data-turbolinks="false" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu
    </div>
    @role('admin')
    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->is('nasabah') || request()->is('nasabah/*') ? 'active' : '' }}">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-store"></i>
            <span>Data Master</span>
        </a>
        <div id="collapse1"
            class="collapse {{ request()->is('nasabah') || request()->is('nasabah/*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('nasabah') ? 'active' : '' }}"
                    href="{{ route('nasabah.index') }}">Data Nasabah</a>

                <!--   <a class="collapse-item" href="/mitra">Data Mitra</a> -->

            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('transaksi/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapse2" class="collapse {{ request()->is('transaksi/*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item {{ request()->is('transaksi/setor') ? 'active' : '' }}"
                    href="{{ route('transaksi.setor') }}">Setor</a>
                <a class="collapse-item {{ request()->is('transaksi/tarik') ? 'active' : '' }}"
                    href="{{ route('transaksi.tarik') }}">Tarik</a>
                <!--   <a class="collapse-item" href="/mitra/tarik">Tarik Saldo Mitra</a> -->
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-store"></i>
            <span>Mitra</span>
        </a>
        <div id="collapse3" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a data-turbolinks="false" class="collapse-item" href="/mitrapay">Transaksi Mitra</a>
            </div>
        </div>
    </li>
    -->

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('laporan/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse4" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-table"></i>
            <span>Laporan</span>
        </a>
        <div id="collapse4" class="collapse {{ request()->is('laporan/*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('laporan/biaya') ? 'active' : '' }}"
                    href="{{ route('laporan.biaya') }}">Biaya</a>
                <!-- <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="laporanumum">Jurnal Umum</a>
                <a class="collapse-item" href="laporanmitra">Jurnal Mitra</a>
                <a class="collapse-item" href="laporanmutasi">Mutasi Nasabah</a>
                -->
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->is('whatapps') ? 'active' : '' }}">
        <a class="nav-link" data-turbolinks="false" href="{{ route('wa') }}">

            <i class="fab fa-whatsapp"></i>
            <span>Whatapps Bot</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('setting/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse5" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Setting</span>
        </a>
        <div id="collapse5" class="collapse {{ request()->is('setting/*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('setting/*') ? 'active' : '' }}"
                    href="{{ route('setting.tabungan') }}">Tabungan</a>
                <!-- <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="/user">User</a>
                <a class="collapse-item" href="#">Guide</a>
                -->
            </div>
        </div>
    </li>




@else

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-store"></i>
            <span>Mitra</span>
        </a>
        <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/mitrapay">Transaksi Kasir</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-table"></i>
            <span>Laporan</span>
        </a>
        <div id="collapse3" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="laporanmitra">Jurnal Mitra</a>
                <a class="collapse-item" href="laporanmutasi">Mutasi Nasabah</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse5" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Setting</span>
        </a>
        <div id="collapse5" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <!-- <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="/user">User</a>
                <a class="collapse-item" href="cards.html">#</a>
                -->
            </div>
        </div>
    </li>

    @endrole



    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();                                         document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>

        <form id="logout" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<!-- Divider -->
