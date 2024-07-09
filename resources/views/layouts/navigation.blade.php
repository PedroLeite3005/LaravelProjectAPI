<!-- Sidebar -->
<div class="sidebar" style="position: fixed">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="{{ route('profile.show') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('transaction') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Transações') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('users.historic') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Histórico') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('stocks.index') }}" class="nav-link">
                    <i class="nav-icon far fa-address-card"></i>
                    <p>
                        {{ __('Comprar') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('stocks.sellList') }}" class="nav-link">
                    <i class="nav-icon far fa-address-card"></i>
                    <p>
                        {{ __('Vender') }}
                    </p>
                </a>
            </li>
             
            <li class="nav-item">
                <a href="{{ route('mercado-livre.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-handshake"></i>
                    <p>
                        {{ __('Mercado Livre') }}
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->