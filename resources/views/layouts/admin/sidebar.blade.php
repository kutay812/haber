<!-- Sidebar -->
<aside class="sidebar">
    <!-- User Profile -->
    <div class="sidebar-profile">
        <div class="profile-image">
            <img src="{{ auth()->user()->profile_image_url }}" alt="Profil Resmi">
        </div>
        <div class="profile-info">
            <div class="profile-name">{{ auth()->user()->name }}</div>
            <div class="profile-role">
                @foreach(auth()->user()->roles as $role)
                    <span class="role-badge">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-newspaper text-white"></i>
        </div>
        <div class="brand-text">Haber Panel</div>
    </div>

    <!-- Ana Menü -->
    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" 
                   href="{{ route('admin.index') }}">
                    <i class="fas fa-home"></i>
                    <span>Ana Sayfa</span>
                </a>
            </li>

            <!-- İçerik Yönetimi Başlığı -->
            <li class="nav-header">İçerik Yönetimi</li>

            <!-- Haberler -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}" 
                   href="{{ route('admin.news.index') }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Haberler</span>
                </a>
            </li>

            <!-- Kategoriler -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                   href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-folder"></i>
                    <span>Kategoriler</span>
                </a>
            </li>

            <!-- Kullanıcı Yönetimi Başlığı -->
            <li class="nav-header">Kullanıcı Yönetimi</li>

            <!-- Kullanıcılar -->
            @can('users.view')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Kullanıcılar</span>
                </a>
            </li>
            @endcan

            <!-- Profil -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" 
                   href="{{ route('admin.profile') }}">
                    <i class="fas fa-user"></i>
                    <span>Profilim</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Çıkış -->
    <div class="sidebar-bottom">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Çıkış Yap</span>
            </button>
        </form>
    </div>
</aside>

<style>
.sidebar {
    display: flex;
    flex-direction: column;
    background: #2c3e50;
    width: 280px;
    min-width: 280px;
    position: sticky;
    top: 0;
    height: 100vh;
}

.sidebar-profile {
    padding: 1.5rem;
    background: rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info {
    flex: 1;
    min-width: 0;
}

.profile-name {
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.3rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.profile-role {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
}

.role-badge {
    background: rgba(52, 152, 219, 0.2);
    color: #fff;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 3px;
    white-space: nowrap;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 1rem 1.5rem;
    background: rgba(0, 0, 0, 0.15);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.brand-icon i {
    font-size: 1.4rem;
    color: #fff;
}

.brand-text {
    color: #fff;
    font-size: 1.2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.sidebar-menu {
    flex: 1;
    padding: 1.5rem 0;
    overflow-y: auto;
}

.nav-header {
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.8rem;
    padding: 1.5rem 1.5rem 0.5rem;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.nav-link {
    color: rgba(255, 255, 255, 0.7) !important;
    padding: 0.8rem 1.5rem;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    font-size: 0.95rem;
}

.nav-link:hover {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.05);
    border-left-color: #3498db;
}

.nav-link.active {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.05);
    border-left-color: #3498db;
}

.nav-link i {
    width: 20px;
    text-align: center;
    margin-right: 0.8rem;
    font-size: 1rem;
}

.sidebar-bottom {
    padding: 1rem;
    background: rgba(0, 0, 0, 0.15);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.logout-btn {
    width: 100%;
    padding: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
    background: rgba(231, 76, 60, 0.1);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}

.logout-btn:hover {
    color: #fff;
    background: rgba(231, 76, 60, 0.2);
}

.logout-btn i {
    margin-right: 0.8rem;
    font-size: 1rem;
}

/* Scrollbar Stilleri */
.sidebar-menu::-webkit-scrollbar {
    width: 4px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}

@media (max-width: 768px) {
    .sidebar {
        width: 80px;
        min-width: 80px;
    }

    .sidebar-profile {
        padding: 1rem;
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .profile-info {
        display: none;
    }

    .profile-image {
        width: 40px;
        height: 40px;
    }

    .nav-link span,
    .logout-btn span,
    .brand-text {
        display: none;
    }

    .nav-link,
    .logout-btn {
        padding: 1rem;
        justify-content: center;
    }

    .nav-link i,
    .logout-btn i {
        margin: 0;
        font-size: 1.2rem;
    }

    .nav-header {
        text-align: center;
        padding: 1rem 0.5rem;
    }

    .sidebar-brand {
        padding: 0.8rem;
        justify-content: center;
    }

    .brand-icon i {
        font-size: 1.2rem;
    }
}
</style>
