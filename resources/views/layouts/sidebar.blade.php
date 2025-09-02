@php
    // Mengambil data RUANGAN (Room) untuk Tawang dari model Room.
    $roomsTawang = \App\Models\Room::where('lokasi', 'tawang')->orderBy('name')->get();
    
    // Mengambil data RUANGAN (Rkl) untuk Lengkongsari dari model Rkl.
    $rklsLengkongsari = \App\Models\Rkl::where('lokasi', 'lengkongsari')->orderBy('name')->get();
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-text mx-3">SINDI</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Kecamatan Tawang
    </div>

    {{-- ====================================================== --}}
    {{-- PERBAIKAN NAMA ROUTE DAN URL DI BAGIAN INI --}}
    {{-- ====================================================== --}}

    <li class="nav-item {{ request()->is('tawang/room*') ? 'active' : '' }}">
        {{-- PERBAIKAN: Diubah menjadi 'tawang.room.index' --}}
        <a class="nav-link" href="{{ route('tawang.room.index') }}">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Data Ruangan</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('tawang/inventaris*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventarisTawang" aria-expanded="true" aria-controls="collapseInventarisTawang">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Inventori Ruangan</span>
        </a>
        
        <div id="collapseInventarisTawang" class="collapse {{ request()->is('tawang/inventaris*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Ruangan:</h6>
                @forelse ($roomsTawang as $room)
                    {{-- PERBAIKAN: Diubah menjadi 'tawang.inventaris.index' --}}
                    <a class="collapse-item {{ request('room_id') == $room->id ? 'active' : '' }}" 
                       href="{{ route('tawang.inventaris.index', ['room_id' => $room->id]) }}">
                        {{ $room->name }}
                    </a>
                @empty
                    {{-- PERBAIKAN: Diubah menjadi 'tawang.room.create' --}}
                    <a class="collapse-item" href="{{ route('tawang.room.create') }}">Tambah Ruangan Dulu</a>
                @endforelse
            </div>
        </div>
    </li>
    
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Kelurahan Lengkongsari
    </div>

    {{-- Bagian Lengkongsari ini sudah benar, tidak perlu diubah --}}
    <li class="nav-item {{ request()->is('lengkongsari/rkl*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('lengkongsari.rkl.index') }}">
            <i class="fas fa-fw fa-door-closed"></i>
            <span>Data Ruangan</span>
        </a>
    </li>

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
    
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

