<!--start sidebar -->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="<?= url('assets/onedash') ?>/images/logo.jpg" class="logo-icon me-2" alt="logo icon"
                style="width: 35px">
        </div>
        <div>
            <h5 class="logo-text text-dark">PT. KUN ANTA</h5>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-list"></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        {{-- Role Manajer Produksi --}}
        @if (session('role') == 'manajer_produksi')
            <li class="{{ Request::is('supervisor/dashboard*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer-produksi/dashboard') ?>">
                    <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            <li class="{{ Request::is('manajer-produksi/supplier*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer-produksi/supplier') ?>">
                    <div class="parent-icon"><i class="bi bi-people"></i></div>
                    <div class="menu-title">Supplier</div>
                </a>
            </li>
            <li class="{{ Request::is('manajer-produksi/bahan-baku*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer-produksi/bahan-baku') ?>">
                    <div class="parent-icon"><i class="bi bi-basket"></i></div>
                    <div class="menu-title">Bahan Baku</div>
                </a>
            </li>
            <li class="{{ Request::is('manajer-produksi/transaksi*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer-produksi/transaksi') ?>">
                    <div class="parent-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="menu-title">Transaksi Bahan Baku</div>
                </a>
            </li>
            <li class="{{ Request::is('manajer-produksi/peramalan*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer-produksi/peramalan') ?>">
                    <div class="parent-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="menu-title">Peramalan</div>
                </a>
            </li>
        @endif

        {{-- Role Supervisor --}}
        @if (session('role') == 'supervisor')
            <li class="{{ Request::is('supervisor/dashboard*') ? 'mm-active' : '' }}">
                <a href="<?= url('supervisor/dashboard') ?>">
                    <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <li class="{{ Request::is('supervisor/transaksi*') ? 'mm-active' : '' }}">
                <a href="<?= url('supervisor/transaksi') ?>">
                    <div class="parent-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="menu-title">Transaksi Bahan Baku</div>
                </a>
            </li>
        @endif

        {{-- Role Admin --}}
        @if (session('role') == 'admin')
            <li class="{{ Request::is('admin/dashboard*') ? 'mm-active' : '' }}">
                <a href="{{ url('admin/dashboard') }}">
                    <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            <li class="{{ Request::is('admin/pengguna*') ? 'mm-active' : '' }}">
                <a href="{{ url('admin/pengguna') }}">
                    <div class="parent-icon"><i class="bi bi-person-circle"></i></div>
                    <div class="menu-title">Pengguna</div>
                </a>
            </li>
            <li class="{{ Request::is('admin/supplier*') ? 'mm-active' : '' }}">
                <a href="{{ url('admin/supplier') }}">
                    <div class="parent-icon"><i class="bi bi-people"></i></div>
                    <div class="menu-title">Supplier</div>
                </a>
            </li>
            <li class="{{ Request::is('admin/bahan-baku*') ? 'mm-active' : '' }}">
                <a href="#" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-box"></i></div>
                    <div class="menu-title">Bahan Baku</div>
                </a>
                <ul>
                    <li class="{{ Request::is('admin/bahan-baku/master*') ? 'mm-active' : '' }}">
                        <a href="{{ url('admin/bahan-baku/master') }}">
                            <i class="bi bi-basket" style="font-size: 15px; margin-left: 25px;"></i> Master
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/bahan-baku/transaksi*') ? 'mm-active' : '' }}">
                        <a href="{{ url('admin/bahan-baku/transaksi') }}">
                            <i class="bi bi-clock-history" style="font-size: 15px; margin-left: 25px;"></i> Transaksi
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/bahan-baku/peramalan*') ? 'mm-active' : '' }}">
                        <a href="{{ url('admin/bahan-baku/peramalan') }}">
                            <i class="bi bi-graph-up" style="font-size: 15px; margin-left: 25px;"></i> Peramalan
                        </a>
                    </li>
                </ul>
            </li>
        @endif


    </ul>
    <!--end navigation-->
</aside>
<!--end sidebar -->