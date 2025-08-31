@php
 // Mengambil data semua ruangan untuk ditampilkan di submenu inventaris.
    $ruangansForSidebar = \App\Models\Room::orderBy('name', 'asc')->get();
@endphp
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
         <div class="sidebar-brand-icon rotate-n-15">
                <!--i class="fas fa-laugh-wink"></i-->
                </div>
                <div class="sidebar-brand-text mx-3">SINDI</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

               <!-- Heading -->
    <div class="sidebar-heading">
        Data Barang
    </div>

    <li class="nav-item {{ request()->is('room*') ? 'active' : '' }}">
        <a class="nav-link" href="/room">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Data Ruangan</span>
        </a>
    </li>

    <!-- Nav Item - Data Inventori Ruangan (Collapsible Menu) -->
    <li class="nav-item {{ request()->is('inventaris*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventaris"
            aria-expanded="true" aria-controls="collapseInventaris">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Inventori Ruangan</span>
        </a>
        
        <div id="collapseInventaris" class="collapse {{ request()->is('inventaris*') ? 'show' : '' }}" aria-labelledby="headingInventaris" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Ruangan:</h6>
                
                @forelse ($ruangansForSidebar as $ruangan)
                    {{-- 
                        PERUBAHAN UTAMA DI SINI:
                        - href sekarang menggunakan route('inventaris.index') dengan parameter 'room_id'.
                        - Pengecekan 'active' sekarang membandingkan request('room_id') dengan $ruangan->id.
                    --}}
                    <a class="collapse-item {{ request('room_id') == $ruangan->id ? 'active' : '' }}" 
                       href="{{ route('inventaris.index', ['room_id' => $ruangan->id]) }}">
                        {{ $ruangan->name }}
                    </a>
                @empty
                    <a class="collapse-item" href="{{ route('room.create') }}">Tambah Ruangan Dulu</a>
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