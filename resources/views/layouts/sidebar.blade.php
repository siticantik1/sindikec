@php 
$roomsTawang = \App\Models\Room::where('lokasi', 'tawang')->orderBy('name')->get();
    $rklsLengkongsari = \App\Models\Rkl::where('lokasi', 'lengkongsari')->orderBy('name')->get();
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-text mx-3">SINDI</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Kecamatan Tawang
    </div>

    <!-- Nav Item - Data Ruangan Tawang -->
    <li class="nav-item {{ request()->is('tawang/room*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('tawang.room.index') }}">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Data Ruangan</span>
        </a>
    </li>

    <!-- Nav Item - Data Inventori Ruangan Tawang (Collapse) -->
    <li class="nav-item {{ request()->is('tawang/inventaris*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventarisTawang" aria-expanded="true" aria-controls="collapseInventarisTawang">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Inventori Ruangan</span>
        </a>
        <div id="collapseInventarisTawang" class="collapse {{ request()->is('tawang/inventaris*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Ruangan:</h6>
                @forelse ($roomsTawang as $room)
                    <a class="collapse-item {{ request('room_id') == $room->id ? 'active' : '' }}"
                       href="{{ route('tawang.inventaris.index', ['room_id' => $room->id]) }}">
                        {{ $room->name }}
                    </a>
                @empty
                    <a class="collapse-item" href="{{ route('tawang.room.create') }}">Tambah Ruangan Dulu</a>
                @endforelse
            </div>
        </div>
    </li>

    <!-- Nav Item - Data Barang Tawang (Collapse) -->
    {{-- PERBAIKAN: Logika 'active' diubah agar tidak bentrok dengan 'barangl*' --}}
    <li class="nav-item {{ request()->is('barang*') && !request()->is('barangl*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsebarang" aria-expanded="true" aria-controls="collapsebarang">
            <i class="fas fa-fw fa-archive"></i>
            <span>Data Barang</span>
        </a>
        <div id="collapsebarang" class="collapse {{ request()->is('barang*') && !request()->is('barangl*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Data:</h6>
                <a class="collapse-item" href="#">Tanah</a>
                <a class="collapse-item" href="#">Peralatan & Mesin</a>
                <a class="collapse-item" href="#">Gedung & Bangunan</a>
                <a class="collapse-item" href="#">Rusak Berat</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Kelurahan Lengkongsari
    </div>
    
    {{-- PERBAIKAN: Tag </li> yang salah tempat dan merusak struktur sudah DIHAPUS dari sini --}}

    <!-- Nav Item - Data Ruangan Lengkongsari -->
    <li class="nav-item {{ request()->is('lengkongsari/rkl*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('lengkongsari.rkl.index') }}">
            <i class="fas fa-fw fa-door-closed"></i>
            <span>Data Ruangan</span>
        </a>
    </li>

    <!-- Nav Item - Data Inventori Ruangan Lengkongsari (Collapse) -->
    <li class="nav-item {{ request()->is('lengkongsari/ikl*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseIklLengkongsari" aria-expanded="true" aria-controls="collapseIklLengkongsari">
            <i class="fas fa-fw fa-archive"></i>
            <span>Data Inventori Ruangan</span>
        </a>
        <div id="collapseIklLengkongsari" class="collapse {{ request()->is('lengkongsari/ikl*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Ruangan:</h6>
                @forelse ($rklsLengkongsari as $rkl)
                    <a class="collapse-item {{ request('rkl_id') == $rkl->id ? 'active' : '' }}"
                       href="{{ route('lengkongsari.ikl.index', ['rkl_id' => $rkl->id]) }}">
                        {{ $rkl->name }}
                    </a>
                @empty
                    <a class="collapse-item" href="{{ route('lengkongsari.rkl.create') }}">Tambah Ruangan Dulu</a>
                @endforelse
            </div>
        </div>
    </li>

    <!-- Nav Item - Data Barang Lengkongsari (Collapse) -->
    {{-- PERBAIKAN: Dipindahkan ke sini agar urutannya logis --}}
    <li class="nav-item {{ request()->is('barangl*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsebarangl" aria-expanded="true" aria-controls="collapsebarangl">
            <i class="fas fa-fw fa-archive"></i>
            <span>Data Barang</span>
        </a>
        <div id="collapsebarangl" class="collapse {{ request()->is('barangl*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Data:</h6>
                <a class="collapse-item" href="#">Tanah</a>
                <a class="collapse-item" href="#">Peralatan & Mesin</a>
                <a class="collapse-item" href="#">Gedung & Bangunan</a>
                <a class="collapse-item" href="#">Rusak Berat</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>